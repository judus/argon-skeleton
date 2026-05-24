<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Routing\Contracts\RouterInterface;

final class RoutingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $router = $container->get(RouterInterface::class);

        $routes = require dirname(__DIR__, 2) . '/routes/web.php';
        $routes($router);
    }
}
