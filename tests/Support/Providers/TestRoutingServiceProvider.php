<?php

declare(strict_types=1);

namespace Tests\Support\Providers;

use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Routing\Contracts\RouterInterface;
use Tests\Support\Http\Controllers\ErrorController;
use Tests\Support\Http\Controllers\HealthController;

final class TestRoutingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $router = $container->get(RouterInterface::class);

        $router->group(['web'], '', static function (RouterInterface $router): void {
            $router->get('/health', HealthController::class, [], 'test.health');
            $router->get('/error', ErrorController::class, [], 'test.error');
        });
    }
}
