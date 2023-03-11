<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Command\CommentCommand;
use Comment\Model\Dto\CommentDto;
use Comment\Service\CommentIdGenerator;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Infra\Symfony6\ORM\Doctrine\Entity\User;
use Infra\Symfony6\Validator\CommentRequestValidator;
use Ramsey\Uuid\Uuid;
use SharedKernel\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Responder\CommentResponder;

#[Route(path: '/api/posts/{id}/comments', name: 'post_comment', methods: ['POST'])]
final class PostCommentAction
{
    private CommandBusInterface $commandBus;
    private CommentIdGenerator $commentIdGenerator;
    private CommentRequestValidator $commentRequestValidator;
    private CommentResponder $responder;

    public function __construct(
        CommandBusInterface $commandBus,
        CommentIdGenerator $commentIdGenerator,
        CommentRequestValidator $commentRequestValidator,
        CommentResponder $responder,
    ) {
        $this->commandBus = $commandBus;
        $this->commentIdGenerator = $commentIdGenerator;
        $this->commentRequestValidator = $commentRequestValidator;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->commentRequestValidator->validate($data);

        // TBD://Fetch real user with JWT / Security
        $user = new User(
            id: Uuid::uuid4(),
            username: $data['username']
        );

        $commentDto = new CommentDto(
            postId: $data['postId'],
            author: new Author((string) $user->getId(), $user->getUserName()),
            commentContent: new CommentContent($data['commentContent'])
        );

        $comment = $this->commandBus->dispatch(
            new CommentCommand(
                commentId: $this->commentIdGenerator->createId(),
                commentDto: $commentDto
            )
        );

        return $this->responder->respond($comment, $request->headers->all());
    }
}
