<?php

declare(strict_types=1);

namespace Application\Command;

use Comment\Model\Dto\RatingDto;
use Comment\ValueObject\Author;
use Comment\ValueObject\CommentId;

final class RateCommand
{
    private CommentId $commentId;
    private string $postId;
    private Author $author;
    private float $rating;

    public function __construct(RatingDto $ratingDto)
    {
        $this->commentId = $ratingDto->getCommentId();
        $this->postId = $ratingDto->getPostId();
        $this->author = $ratingDto->getAuthor();
        $this->rating = $ratingDto->getCommentRating();
    }

    public function getRating(): float
    {
        return $this->rating;
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
}
