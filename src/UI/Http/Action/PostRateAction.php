<?php

declare(strict_types=1);

namespace UI\Http\Action;

use Application\Command\RateCommand;
use Comment\Model\Dto\RatingDto;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentId;
use Infra\Symfony6\Validator\RateRequestValidator;
use Ramsey\Uuid\Uuid;
use SharedKernel\Application\Command\CommandBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UI\Http\Responder\RateResponder;

final class PostRateAction
{
    private CommandBusInterface $commandBus;
    private RateRequestValidator $rateRequestValidator;
    private RateResponder $responder;

    public function __construct(
        CommandBusInterface $commandBus,
        RateRequestValidator $rateRequestValidator,
        RateResponder $responder
    ) {
        $this->commandBus = $commandBus;
        $this->rateRequestValidator = $rateRequestValidator;
        $this->responder = $responder;
    }

    public function __invoke(Request $request, string $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->rateRequestValidator->validate($data);
        $user = $request->getUser();

        $ratedComment = $this->commandBus->dispatch(
            new RateCommand(
                ratingDto: new RatingDto(
                    commentId: new CommentId(Uuid::fromString($id)),
                    postId: $data['postId'],
                    author: new Author((string) $user->getId(), $user->getUserName()),
                    commentRating: $data['commentRating']
                )
            )
        );
        return $this->responder->respond($ratedComment, $request->headers->all());
    }
}
