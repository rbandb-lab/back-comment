<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\CommentCommand;
use Comment\Model\Comment;
use Comment\Repository\CommentRepository;
use SharedKernel\Application\Command\CommandHandler;

final class CommentCommandHandler implements CommandHandler
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(CommentCommand $command): Comment
    {
        $comment = new Comment(
            commentId: $command->getCommentId(),
            postId: $command->getPostId(),
            author: $command->getAuthor(),
            commentContent: $command->getCommentContent()->getContent()
        );

        $this->commentRepository->save($comment);
        return $comment;
    }
}
