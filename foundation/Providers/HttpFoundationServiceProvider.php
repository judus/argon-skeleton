<?php

declare(strict_types=1);

namespace Foundation\Providers;

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
        $parameters = $container->getParameters();
        $parameters->set('kernel.shouldExit', $parameters->get('app.env', 'production') !== 'testing');
    }

    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->register(ConfigServiceProvider::class);
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
