<?php

declare(strict_types=1);

namespace Foundation\Http\Controllers;

final readonly class HealthController
{
    /**
     * @return array{status: string, runtime: string}
     */
    public function __invoke(): array
    {
        return [
            'status' => 'ok',
            'runtime' => 'argon',
        ];
    }
}
