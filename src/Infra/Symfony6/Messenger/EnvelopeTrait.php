<?php

declare(strict_types=1);

namespace Infra\Symfony6\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;

trait EnvelopeTrait
{
    private function handle(Envelope $envelope): mixed
    {
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new \LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected when using "%s::%s()".', \get_class($envelope->getMessage()), \get_class($this), __FUNCTION__));
        }

        if (\count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(function (HandledStamp $stamp): string {
                return sprintf('"%s"', $stamp->getHandlerName());
            }, $handledStamps));

            throw new \LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', \get_class($envelope->getMessage()), \get_class($this), __FUNCTION__, \count($handledStamps), $handlers));
        }

        return $handledStamps[0]->getResult();
    }
}
