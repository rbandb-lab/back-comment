<?php

declare(strict_types=1);

namespace UI\Http\Responder;

use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class ApiResponder
{
    private PropertyAccessor $accessor;

    public function __construct(PropertyAccessor $accessor)
    {
        $this->accessor = $accessor;
    }

    public function acceptsJson(mixed $headers): bool
    {
        $accept = $this->accessor->getValue($headers, '[accept?]');
        return strtolower(trim($accept[0])) === 'application/json';
    }
}
