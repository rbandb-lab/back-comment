<?php

declare(strict_types=1);

namespace SharedKernel\Responder;

interface HttpResponder
{
    public function acceptsJson(mixed $headers): bool;
}
