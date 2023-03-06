<?php

declare(strict_types=1);

namespace Infra\Symfony6\Service;

use Comment\Service\CommentIdGenerator as IdGeneratorInterface;
use Comment\ValueObject\CommentId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CommentIdGenerator implements IdGeneratorInterface
{
    public function createId(UuidInterface|string|null $id = null): CommentId
    {
        if ($id instanceof UuidInterface) {
            return new CommentId($id);
        }

        $id = match (gettype($id)) {
            'string' => Uuid::isValid($id) ? Uuid::fromString($id) : Uuid::uuid4(),
            default => Uuid::uuid4()
        };

        return new CommentId($id);
    }
}
