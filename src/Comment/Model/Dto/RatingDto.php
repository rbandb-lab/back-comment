<?php

declare(strict_types=1);

namespace Comment\Model\Dto;

use Comment\ValueObject\Author;
use Comment\ValueObject\CommentId;

final class RatingDto
{
    private CommentId $commentId;
    private string $postId;
    private Author $author;
    private int $commentRating;

    public function __construct(
        CommentId $commentId,
        string $postId,
        Author $author,
        int $commentRating
    ) {
        $this->commentId = $commentId;
        $this->postId = $postId;
        $this->author = $author;
        $this->commentRating = $commentRating;
    }

    public function getCommentId(): CommentId
    {
        return $this->commentId;
    }

    public function getCommentRating(): int
    {
        return $this->commentRating;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
