<?php

declare(strict_types=1);

namespace Application\Query;

use SharedKernel\Application\Query\QueryInterface;

class LatestQuery implements QueryInterface
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
