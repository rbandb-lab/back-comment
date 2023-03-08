<?php

declare(strict_types=1);

namespace Comment\ValueObject;

use Assert\Assert;
use Comment\Exception\InvalidCommentRatingException;
use Doctrine\ORM\Mapping as ORM;

final class CommentRating
{
    private Author $ratingAuthor;
    private float $rate;
    private int $createdAt;

    public function __construct(Author $ratingAuthor, float $rate)
    {
        $this->ratingAuthor = $ratingAuthor;
        try {
            Assert::lazy()
                ->that($rate, 'rating')
                ->min(0, 'must be positive')
                ->max(10, 'cannot exceed 10')
                ->verifyNow();
        } catch (\Exception $exception) {
            throw new InvalidCommentRatingException(sprintf($exception->getPropertyPath().'%s'.$exception->getMessage(), " "));
        }

        $this->rate = $rate;
    }

    public function getRatingAuthor(): Author
    {
        return $this->ratingAuthor;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
