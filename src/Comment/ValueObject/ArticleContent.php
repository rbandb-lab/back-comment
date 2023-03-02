<?php

declare(strict_types=1);

namespace Comment\ValueObject;

class ArticleContent
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
