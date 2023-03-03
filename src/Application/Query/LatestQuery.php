<?php

declare(strict_types=1);

namespace Application\Query;

class LatestQuery
{
    private int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
