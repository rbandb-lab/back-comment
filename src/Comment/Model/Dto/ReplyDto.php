<?php

declare(strict_types=1);

namespace Comment\Model\Dto;

use Comment\ValueObject\Author;
use Comment\ValueObject\CommentContent;
use Comment\ValueObject\CommentId;
use Ramsey\Uuid\Uuid;

final class ReplyDto
{
    private string $postId;
    private Author $author;
    private CommentContent $commentContent;
    private CommentId|string $parentId;

    public function __construct(
        string $postId,
        Author $author,
        CommentContent $commentContent,
        CommentId|string $parentId = null
    ) {
        $this->postId = $postId;
        $this->author = $author;
        $this->commentContent = $commentContent;
        $this->parentId = $parentId;
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
