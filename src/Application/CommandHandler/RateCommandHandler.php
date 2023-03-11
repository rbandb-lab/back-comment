<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\RateCommand;
use Comment\Exception\CannotRateCommentTwiceException;
use Comment\Model\Comment;
use Comment\Repository\CommentRepository;
use Comment\ValueObject\CommentRating;
use SharedKernel\Application\Command\CommandHandler;

final class RateCommandHandler implements CommandHandler
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(RateCommand $command): Comment
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($command->getCommentId());
        $ratings = $comment->getRatings();

        foreach ($ratings->getIterator() as $rating) {
            $author = $rating->getRatingAuthor();
            if ($author->id === $command->getAuthor()->getId()) {
                throw new CannotRateCommentTwiceException();
            }
        }

        $comment->addRating(
            new CommentRating(
                ratingAuthor: $command->getAuthor(),
                rate: $command->getRating()
            )
        );

        $this->commentRepository->save($comment);

        return $comment;
    }
}
