<?php

declare(strict_types=1);

namespace Foundation\Providers;

use Dotenv\Dotenv;
use Maduser\Argon\Container\AbstractServiceProvider;
use Maduser\Argon\Container\ArgonContainer;

final class ConfigServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $basePath = dirname(__DIR__, 2);

        if (is_file($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->safeLoad();
        }

        $parameters = $container->getParameters();
        $parameters->set('app.name', $this->envString('APP_NAME', 'Argon App'));
        $parameters->set('app.env', $this->envString('APP_ENV', 'production'));
        $parameters->set('app.debug', $this->envBool('APP_DEBUG', false));
        $parameters->set('app.version', $this->envString('APP_VERSION', '0.1.0'));
    }

    private function envString(string $key, string $default): string
    {
        $value = $_ENV[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : $default;
    }

    private function envBool(string $key, bool $default): bool
    {
        $value = $_ENV[$key] ?? null;

        if ($value === null || $value === '') {
            return $default;
        }

        $result = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        return is_bool($result) ? $result : $default;
    }
}
