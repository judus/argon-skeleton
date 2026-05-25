<?php

declare(strict_types=1);

namespace Tests\Support\Http\Controllers;

use RuntimeException;

final readonly class ErrorController
{
    public function __invoke(): never
    {
        throw new RuntimeException('This exception was rendered by the application exception policy.', 503);
    }
}
