<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Routing\Contracts\RouterInterface;

/**
 * Loads application route declarations into the already-registered router.
 */
final class AppRoutingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $router = $container->get(RouterInterface::class);

        /** @var callable(RouterInterface): void $routes */
        $routes = require dirname(__DIR__, 2) . '/routes/web.php';
        $routes($router);
    }
}
