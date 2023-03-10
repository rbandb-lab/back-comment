<?php

declare(strict_types=1);

namespace Comment\Service;

use Comment\ValueObject\CommentId;
use Ramsey\Uuid\UuidInterface;

interface CommentIdGenerator
{
    public function createId(UuidInterface|string|null $id = null): CommentId;
}
