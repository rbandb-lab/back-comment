<?php

declare(strict_types=1);

namespace Infra\Symfony6\Controller;

use Symfony\Component\HttpFoundation\Response;

class NelmioApiDocController
{
    public function __invoke()
    {
        return new Response();
    }
}
