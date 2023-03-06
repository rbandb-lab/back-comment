<?php

declare(strict_types=1);

namespace Application\Query;

use SharedKernel\Application\Query\QueryInterface;

final class ByPostQuery implements QueryInterface
{
    private string $postId;

    public function __construct(string $postId)
    {
        $this->postId = $postId;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }
}
