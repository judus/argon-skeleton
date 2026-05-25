<?php

declare(strict_types=1);

namespace Tests\Skeleton\Feature;

use InvalidArgumentException;
use Tests\Skeleton\ApplicationTestCase;

final class ContainerTest extends ApplicationTestCase
{
    /**
     * @var array<non-empty-string, string|null>
     */
    private array $envBackup = [];

    #[\Override]
    protected function tearDown(): void
    {
        foreach ($this->envBackup as $key => $value) {
            if ($value === null) {
                unset($_ENV[$key]);

                continue;
            }

            $_ENV[$key] = $value;
        }

        $this->envBackup = [];
    }

    public function testApplicationParametersAreAvailableInContainer(): void
    {
        $container = $this->boot('http');
        $parameters = $container->getParameters();

        self::assertSame('Argon App', $parameters->get('app.name'));
        self::assertSame('production', $parameters->get('app.env'));
        self::assertSame('0.1.0', $parameters->get('app.version'));
    }

    public function testApplicationParametersCanBeConfiguredFromEnvironment(): void
    {
        $this->setEnv('APP_NAME', 'Custom Argon');
        $this->setEnv('APP_ENV', 'testing');
        $this->setEnv('APP_DEBUG', 'true');
        $this->setEnv('APP_VERSION', '2.3.4');

        $container = $this->boot('http');
        $parameters = $container->getParameters();

        self::assertSame('Custom Argon', $parameters->get('app.name'));
        self::assertSame('testing', $parameters->get('app.env'));
        self::assertTrue($parameters->get('app.debug'));
        self::assertSame('2.3.4', $parameters->get('app.version'));
        self::assertFalse($parameters->get('kernel.shouldExit'));
    }

    public function testApplicationParametersFallbackWhenEnvironmentValuesAreInvalid(): void
    {
        $this->setEnv('APP_NAME', '');
        $this->setEnv('APP_ENV', '');
        $this->setEnv('APP_DEBUG', 'definitely');
        $this->setEnv('APP_VERSION', '');

        $container = $this->boot('http');
        $parameters = $container->getParameters();

        self::assertSame('Argon App', $parameters->get('app.name'));
        self::assertSame('production', $parameters->get('app.env'));
        self::assertFalse($parameters->get('app.debug'));
        self::assertSame('0.1.0', $parameters->get('app.version'));
    }

    public function testUnsupportedRuntimeFailsExplicitly(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported Argon runtime [worker].');

        $this->boot('worker');
    }

    /**
     * @param non-empty-string $key
     */
    private function setEnv(string $key, string $value): void
    {
        if (!array_key_exists($key, $this->envBackup)) {
            $previous = $_ENV[$key] ?? null;
            $this->envBackup[$key] = is_string($previous) ? $previous : null;
        }

        $_ENV[$key] = $value;
    }
}
