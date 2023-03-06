<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Command\ReplyCommand;
use Comment\Model\Dto\ReplyDto;
use Comment\Service\CommentIdGenerator;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Infra\Symfony6\Validator\ReplyRequestValidator;
use SharedKernel\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UI\Http\Responder\ReplyResponder;

final class PostReplyAction
{
    private CommentIdGenerator $commentIdGenerator;
    private CommandBusInterface $commandBus;
    private ReplyRequestValidator $replyRequestValidator;
    private ReplyResponder $responder;

    public function __construct(
        CommentIdGenerator $commentIdGenerator,
        CommandBusInterface $commandBus,
        ReplyRequestValidator $replyRequestValidator,
        ReplyResponder $responder
    ) {
        $this->commentIdGenerator = $commentIdGenerator;
        $this->commandBus = $commandBus;
        $this->replyRequestValidator = $replyRequestValidator;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->replyRequestValidator->validate($data);
        $user = $request->getUser();

        $reply = $this->commandBus->dispatch(
            new ReplyCommand(
                id: $this->commentIdGenerator->createId(),
                replyDto: new ReplyDto(
                    postId: $data['postId'],
                    author: new Author((string) $user->getId(), $user->getUserName()),
                    commentContent: new CommentContent($data['commentContent']),
                    parentId: $data['parentId']
                )
            )
        );

        return $this->responder->respond($reply, $request->headers->all());
    }
}