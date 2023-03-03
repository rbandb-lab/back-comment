<?php

declare(strict_types=1);

namespace Infra\Symfony6\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $class = get_class($envelope->getMessage());

        $this->logger->info(sprintf('Start handling message of class "%s"', $class));
        try {
            $result = $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $exception) {
            foreach ($exception->getNestedExceptions() as $e) {
                $this->logger->error(sprintf('Exception thrown when handling message of class "%s"', $class), ['exception' => $e, 'exception_message' => $e->getMessage(), 'exception_class' => get_class($e)]);
            }

            throw $exception;
        } catch (\Throwable $exception) {
            $this->logger->error(sprintf('Exception thrown when handling message of class "%s"', $class), ['exception' => $exception, 'exception_message' => $exception->getMessage(), 'exception_class' => get_class($exception)]);

            throw $exception;
        }

        $this->logger->info(sprintf('Successfully handled message of class "%s"', $class));

        return $result;
    }
}
