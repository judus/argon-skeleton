<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Http\Controllers\ErrorController;
use Foundation\Http\Controllers\HealthController;
use Foundation\Http\Controllers\HomeController;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Routing\Contracts\RouterInterface;

/**
 * Register application routes here.
 */
final class AppRoutingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $router = $container->get(RouterInterface::class);

        $router->group(['web'], '', static function (RouterInterface $router): void {
            $router->get('/', HomeController::class, [], 'home');
            $router->get('/health', HealthController::class, [], 'health');
            $router->get('/error', ErrorController::class, [], 'error');
        });
    }
}
