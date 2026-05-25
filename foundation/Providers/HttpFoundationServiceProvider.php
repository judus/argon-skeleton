<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Dotenv\Dotenv;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Http\Message\Provider\HttpMessageServiceProvider;
use Maduser\Argon\Http\Provider\HttpKernelServiceProvider;
use Maduser\Argon\Middleware\Provider\MiddlewarePipelineServiceProvider;
use Maduser\Argon\Routing\Provider\RouteServiceProvider;

final class HttpFoundationServiceProvider extends AbstractServiceProvider
{
    private function configure(ArgonContainer $container): void
    {
        $basePath = dirname(__DIR__, 2);

        if (is_file($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->safeLoad();
        }

        $app = require $basePath . '/config/app.php';

        $parameters = $container->getParameters();
        $parameters->set('app.name', $app['name']);
        $parameters->set('app.env', $app['env']);
        $parameters->set('app.debug', $app['debug']);
        $parameters->set('app.version', $app['version']);
        $parameters->set('kernel.shouldExit', $app['env'] !== 'testing');
    }

    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $this->configure($container);

        $container->register([
            LoggingServiceProvider::class,
            ErrorHandlingServiceProvider::class,
            HttpMessageServiceProvider::class,
            MiddlewarePipelineServiceProvider::class,
            RouteServiceProvider::class,
            HttpKernelServiceProvider::class,
            AppServiceProvider::class,
            MiddlewareServiceProvider::class,
            AppRoutingServiceProvider::class,
        ]);
    }
}
