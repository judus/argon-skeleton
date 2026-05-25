<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Dotenv\Dotenv;
use Maduser\Argon\Console\Provider\ConsoleServiceProvider;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class ConsoleFoundationServiceProvider extends AbstractServiceProvider
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
        $parameters->set('console.name', $app['name']);
        $parameters->set('console.version', $app['version']);
    }

    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $this->configure($container);

        $container->register([
            LoggingServiceProvider::class,
            ConsoleServiceProvider::class,
            AppServiceProvider::class,
            ConsoleCommandServiceProvider::class,
        ]);
    }
}
