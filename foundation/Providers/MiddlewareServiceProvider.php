<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Http\Middleware\SecurityHeadersMiddleware;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class MiddlewareServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->set(SecurityHeadersMiddleware::class)
            ->tag(['middleware.http' => ['group' => 'web', 'priority' => 6100]]);
    }
}
