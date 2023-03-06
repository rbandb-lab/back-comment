<?php

declare(strict_types=1);

namespace Infra\Symfony6\Messenger;

use SharedKernel\Application\Command\CommandBusInterface;
use SharedKernel\Application\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class SfMessengerCommandBus implements CommandBusInterface
{
    use EnvelopeTrait;

    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        try {
            return $this->handle(
                $this->commandBus->dispatch($command)
            );
        } catch (HandlerFailedException $e) {
            /** @var array{0: \Throwable} $exceptions */
            $exceptions = $e->getNestedExceptions();

            throw $exceptions[0];
        }
    }
}
