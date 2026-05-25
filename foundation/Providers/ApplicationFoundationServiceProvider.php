<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Dotenv\Dotenv;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Container\Contracts\ServiceProviderInterface;

final class ApplicationFoundationServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $basePath = dirname(__DIR__, 2);

        if (is_file($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->safeLoad();
        }

        $app = require $basePath . '/config/app.php';
        /** @var list<class-string<ServiceProviderInterface>> $providers */
        $providers = require $basePath . '/config/providers.php';

        $parameters = $container->getParameters();
        $parameters->set('app.name', $app['name']);
        $parameters->set('app.env', $app['env']);
        $parameters->set('app.debug', $app['debug']);
        $parameters->set('app.version', $app['version']);
        $parameters->set('kernel.shouldExit', $app['env'] !== 'testing');

        $container->register($providers);
    }
}
