<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\ReplyCommand;
use Comment\Model\Comment;
use Comment\Repository\CommentRepository;

final class ReplyCommandHandler
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(ReplyCommand $command): Comment
    {
        $comment = new Comment(
            commentId: $command->getCommentId(),
            postId: $command->getPostId(),
            author: $command->getAuthor(),
            commentContent: $command->getCommentContent()->getContent(),
        );

        /** @var Comment $parent */
        $parent = $this->commentRepository->find($command->getParentId());
        $comment->setParentId($parent->getCommentId());
        $parent->addSubComment($comment);

        $this->commentRepository->save($comment);
        $this->commentRepository->save($parent);

        return $comment;
    }
}
