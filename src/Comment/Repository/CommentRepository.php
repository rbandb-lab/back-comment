<?php

declare(strict_types=1);

namespace Comment\Repository;

interface CommentRepository
{
    public function find(string $id);
}
