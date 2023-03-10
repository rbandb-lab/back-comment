<?php

declare(strict_types=1);

namespace Comment\ValueObject;

class Author
{
    private string $id;
    private string $username;

    public function __construct(string $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
