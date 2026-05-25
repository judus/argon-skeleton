<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Console\Command\AboutCommand;
use Maduser\Argon\Console\Provider\ConsoleServiceProvider;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class ConsoleFoundationServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->register(ApplicationFoundationServiceProvider::class);

        $parameters = $container->getParameters();

        $parameters->set('console.name', (string) $parameters->get('app.name', 'Argon Console'));
        $parameters->set('console.version', (string) $parameters->get('app.version', 'UNKNOWN'));

        $container->register(ConsoleServiceProvider::class);

        $container->set(AboutCommand::class)
            ->tag([ConsoleServiceProvider::COMMAND_TAG]);
    }
}
