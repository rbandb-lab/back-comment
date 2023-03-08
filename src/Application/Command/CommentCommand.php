<?php

declare(strict_types=1);

namespace Application\Command;

use Comment\Model\Dto\CommentDto;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Comment\ValueObject\CommentId;
use SharedKernel\Application\Command\CommandInterface;

final class CommentCommand implements CommandInterface
{
    private CommentId $commentId;
    private string $postId;
    private Author $author;
    private CommentContent $commentContent;


    public function __construct(
        CommentId $commentId,
        CommentDto $commentDto
    ) {
        $this->commentId = $commentId;
        $this->postId = $commentDto->getPostId();
        $this->author = $commentDto->getAuthor();
        $this->commentContent = $commentDto->getCommentContent();
    }

    public function getCommentId(): CommentId
    {
        return $this->commentId;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }


    public function getCommentContent(): CommentContent
    {
        return $this->commentContent;
    }
}
