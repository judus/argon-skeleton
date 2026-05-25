<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Maduser\Argon\Console\Provider\ConsoleServiceProvider;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class ConsoleFoundationServiceProvider extends AbstractServiceProvider
{
    private function configure(ArgonContainer $container): void
    {
        $parameters = $container->getParameters();
        $parameters->set('console.name', (string) $parameters->get('app.name', 'Argon Console'));
        $parameters->set('console.version', (string) $parameters->get('app.version', 'UNKNOWN'));
    }

    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->register(ConfigServiceProvider::class);
        $this->configure($container);

        $container->register([
            LoggingServiceProvider::class,
            ConsoleServiceProvider::class,
            AppServiceProvider::class,
            ConsoleCommandServiceProvider::class,
        ]);
    }
}
