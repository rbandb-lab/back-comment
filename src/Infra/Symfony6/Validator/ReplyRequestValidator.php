<?php

declare(strict_types=1);

namespace Infra\Symfony6\Validator;

use Assert\Assert;

class ReplyRequestValidator
{
    public function validate(array $data): void
    {
        Assert::lazy()
            ->that($data)
            ->keyExists('postId', 'Payload must contain the postId parameter')
            ->string('postId must be of type string')
            ->length(36)
            ->that($data)
            ->keyExists('parentId', 'Payload must contain the parent comment id parameter')
            ->string('parentId must be of type string')
            ->length(36)
            ->that($data)
            ->keyExists('commentContent', 'Payload must contain the commentContent parameter')
            ->lessThan(255, 'commentContent length cannot excess 255 characters')
            ->greaterThan(0, 'commentContent length cannot be a blank string')
            ->verifyNow()
        ;
    }
}
