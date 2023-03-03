<?php

declare(strict_types=1);

namespace Comment\Repository;

interface PostRepository
{
    public function find(string $id);
}
