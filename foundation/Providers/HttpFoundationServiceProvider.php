<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Http\Middleware\SecurityHeadersMiddleware;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Http\Message\Provider\HttpMessageServiceProvider;
use Maduser\Argon\Http\Provider\HttpKernelServiceProvider;
use Maduser\Argon\Middleware\Provider\MiddlewarePipelineServiceProvider;
use Maduser\Argon\Routing\Provider\RouteServiceProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class HttpFoundationServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->set(LoggerInterface::class, NullLogger::class)->shared();

        $container->register([
            ApplicationFoundationServiceProvider::class,
            ErrorHandlingServiceProvider::class,
        ]);

        $container->register([
            HttpMessageServiceProvider::class,
            MiddlewarePipelineServiceProvider::class,
            RouteServiceProvider::class,
            HttpKernelServiceProvider::class,
        ]);

        $container->set(SecurityHeadersMiddleware::class)
            ->tag(['middleware.http' => ['group' => 'web', 'priority' => 6100]]);

        $container->register(AppRoutingServiceProvider::class);
    }
}
