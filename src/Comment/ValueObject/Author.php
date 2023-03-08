<?php

declare(strict_types=1);

namespace Comment\ValueObject;

use Doctrine\ORM\Mapping as ORM;

final class Author
{
    public string $id;
    public string $username;

    public function __construct(string $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }
}
