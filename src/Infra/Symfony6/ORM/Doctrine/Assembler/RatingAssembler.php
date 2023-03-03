<?php

declare(strict_types=1);

namespace Infra\Symfony6\ORM\Doctrine\Assembler;

use Comment\ValueObject\Author;
use Comment\ValueObject\CommentRating;
use Infra\Symfony6\ORM\Doctrine\Entity\Rating;

class RatingAssembler
{
    public static function fromOrm(Rating $rating): CommentRating
    {
        $user = $rating->getUser();
        return new CommentRating(
            ratingAuthor: new Author(
                id: (string) $user->getId(),
                username: $user->getUsername()
            ),rate: $rating->getValue()
        );
    }
}
