<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Foundation\Exceptions\AppExceptionPolicy;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Error\Contracts\ExceptionPolicyInterface;
use Maduser\Argon\Error\Provider\ErrorHandlerServiceProvider;

final class ErrorHandlingServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->register(ErrorHandlerServiceProvider::class);

        $container->set(AppExceptionPolicy::class)
            ->tag([ExceptionPolicyInterface::class]);
    }
}
