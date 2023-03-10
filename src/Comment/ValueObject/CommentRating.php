<?php

declare(strict_types=1);

namespace Comment\ValueObject;

use Assert\Assert;

class CommentRating
{
    private Author $ratingAuthor;
    private float $rate;

    public function __construct(Author $ratingAuthor, float $rate)
    {
        $this->ratingAuthor = $ratingAuthor;
        try {
            Assert::lazy()
                ->that($rate, 'rating')
                ->max(5, 'cannot exceed 5')
                ->verifyNow();
            Assert::lazy()
                ->that($rate * 2 - floor($rate * 2))
                ->eq(0)
                ->verifyNow();
        } catch (\Exception $exception) {
            dd($exception);
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
