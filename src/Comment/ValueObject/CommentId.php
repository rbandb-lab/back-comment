<?php

declare(strict_types=1);

namespace Comment\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

final class CommentId
{
    private string|UuidInterface $id;

    public function __construct(string|UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getUuid(): UuidInterface
    {
        return (is_string($this->id)) ? Uuid::fromString($this->id) : $this->id;
    }

    public function __toString(): string
    {
        return (string)($this->id);
    }
}
