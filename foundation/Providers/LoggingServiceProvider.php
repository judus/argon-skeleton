<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class LoggingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->set(LoggerInterface::class, NullLogger::class)->shared();
    }
}
