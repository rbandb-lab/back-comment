<?php

declare(strict_types=1);

namespace Comment\ValueObject;

final class PostContent
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
