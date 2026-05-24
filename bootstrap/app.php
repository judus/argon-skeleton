<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Foundation\Providers\ConsoleFoundationServiceProvider;
use Foundation\Providers\ErrorHandlingServiceProvider;
use Foundation\Providers\HttpFoundationServiceProvider;
use Foundation\Providers\RoutingServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Container\Contracts\ServiceProviderInterface;

$basePath = dirname(__DIR__);

if (is_file($basePath . '/.env')) {
    Dotenv::createImmutable($basePath)->safeLoad();
}

$app = require $basePath . '/config/app.php';
/** @var list<class-string<ServiceProviderInterface>> $providers */
$providers = require $basePath . '/config/providers.php';

return static function (string $runtime) use ($app, $providers): Closure {
    return static function (ArgonContainer $container) use ($app, $providers, $runtime): void {
        $parameters = $container->getParameters();

        $parameters->set('app.name', $app['name']);
        $parameters->set('app.env', $app['env']);
        $parameters->set('app.debug', $app['debug']);
        $parameters->set('app.version', $app['version']);
        $parameters->set('kernel.shouldExit', $app['env'] !== 'testing');

        $container->register($providers);

        match ($runtime) {
            'http' => $container->register([
                ErrorHandlingServiceProvider::class,
                HttpFoundationServiceProvider::class,
                RoutingServiceProvider::class,
            ]),
            'console' => $container->register(ConsoleFoundationServiceProvider::class),
            default => throw new InvalidArgumentException(sprintf('Unsupported Argon runtime [%s].', $runtime)),
        };
    };
};
