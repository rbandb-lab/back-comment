<?php

declare(strict_types=1);

namespace SharedKernel\Application\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
