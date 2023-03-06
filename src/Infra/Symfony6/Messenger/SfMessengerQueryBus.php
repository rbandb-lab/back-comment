<?php

declare(strict_types=1);

namespace Infra\Symfony6\Messenger;

use SharedKernel\Application\Query\QueryBusInterface;
use SharedKernel\Application\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

class SfMessengerQueryBus implements QueryBusInterface
{
    use EnvelopeTrait;

    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function dispatch(QueryInterface $query): mixed
    {
        try {
            return $this->handle(
                $this->queryBus->dispatch($query)
            );
        } catch (HandlerFailedException $e) {
            /** @var array{0: \Throwable} $exceptions */
            $exceptions = $e->getNestedExceptions();
            throw $exceptions[0];
        }
    }
}
