<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class ApiResponder
{
    private PropertyAccessorInterface $accessor;

    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    public function acceptsJson(mixed $headers): bool
    {
        $accept = $this->accessor->getValue($headers, '[accept?]');

        return strtolower(trim($accept[0])) === 'application/json';
    }
}
