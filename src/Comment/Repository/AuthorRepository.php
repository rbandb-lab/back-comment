<?php

declare(strict_types=1);

namespace Comment\Repository;

interface AuthorRepository
{
    public function find(string $id);
}
