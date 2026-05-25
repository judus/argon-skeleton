<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Console\Command\AboutCommand;
use Maduser\Argon\Console\Provider\ConsoleServiceProvider;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class ConsoleCommandServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->set(AboutCommand::class)
            ->tag([ConsoleServiceProvider::COMMAND_TAG]);
    }
}
