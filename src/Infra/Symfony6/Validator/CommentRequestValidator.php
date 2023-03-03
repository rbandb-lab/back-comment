<?php

declare(strict_types=1);

namespace Infra\Symfony6\Validator;

use Assert\Assert;
use Assert\LazyAssertion;
use Symfony\Component\String\UnicodeString;

class CommentRequestValidator
{
    public function validate(array $data): void
    {
        Assert::lazy()
            ->that($data)
            ->keyExists('postId', 'Payload must contain the postId parameter')
            ->that($data['postId'])
            ->string('postId must be of type string')
            ->that($data)
            ->keyExists('commentContent', 'Payload must contain the commentContent parameter')
            ->that($data['commentContent'])
            ->maxLength(255, 'commentContent length cannot excess 255 characters')
            ->minLength(0, 'commentContent length cannot be a blank string')
            ->verifyNow()
        ;
    }
}
