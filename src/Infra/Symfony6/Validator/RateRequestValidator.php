<?php

declare(strict_types=1);

namespace Infra\Symfony6\Validator;

use Assert\Assert;

class RateRequestValidator
{
    public function validate(array $data): void
    {
        Assert::lazy()
            ->that($data)
            ->keyExists('postId', 'Payload must contain the postId parameter')
            ->string('postId must be of type string')
            ->length(36)
            ->that($data)
            ->keyExists('commentRating', 'Payload must contain the commentRating parameter')
            ->integer('must be an integer')
            ->greaterThan(0, 'commentRating must be positive')
            ->lessThan(0, 'commentRating cannot be greater than 10')
            ->verifyNow()
        ;
    }
}
