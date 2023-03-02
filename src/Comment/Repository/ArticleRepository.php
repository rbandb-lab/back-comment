<?php

declare(strict_types=1);

namespace Comment\Repository;

interface ArticleRepository
{
    public function find(string $id);
}
