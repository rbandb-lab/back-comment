<?php

declare(strict_types=1);

namespace Application\Query;

final class ByPostQuery
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
