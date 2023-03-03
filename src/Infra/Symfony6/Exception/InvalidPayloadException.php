<?php

declare(strict_types=1);

namespace Infra\Symfony6\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidPayloadException extends HttpException
{
}
