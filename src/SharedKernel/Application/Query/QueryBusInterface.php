<?php

declare(strict_types=1);

namespace SharedKernel\Application\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query): mixed;
}
