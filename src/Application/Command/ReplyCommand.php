<?php

declare(strict_types=1);

namespace Application\Command;

use Comment\Model\Dto\CommentDto;
use Comment\Model\Dto\ReplyDto;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Comment\ValueObject\CommentId;
use Ramsey\Uuid\UuidInterface;

final class ReplyCommand
{
    private CommentId|string $id;
    private string $postId;
    private Author $author;
    private CommentContent $commentContent;
    private CommentId|string $parentId;

    public function __construct(CommentId|string $id, ReplyDto $replyDto)
    {
        $this->id = $id;
        $this->postId = $replyDto->getPostId();
        $this->author = $replyDto->getAuthor();
        $this->commentContent = $replyDto->getCommentContent();
        $this->parentId = $replyDto->getParentId();
    }

    public function getCommentId(): CommentId|string
    {
        return $this->id;
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

    public function getParentId(): CommentId|string
    {
        return $this->parentId;
    }
}
