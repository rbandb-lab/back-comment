<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Comment\ValueObject\Author;

class AuthContext implements Context
{
    protected ?Author $author = null;
    public function __construct()
    {
        $this->author = new Author(id: "1-john", username: "John Doe");
    }

    public function isIdentified(Author $author): bool
    {
        if ($author->id === $this->author?->id && $author->id !== null) {
            return true;
        }
        return false;
    }

    /**
     * @Given the author :arg1 with id :arg2 is not identified
     */
    public function theAuthorWithIdIsNotIdentified($arg1, $arg2)
    {
        return $this->isIdentified(new Author($arg2, $arg1));
    }
}
