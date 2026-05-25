<?php

declare(strict_types=1);

namespace Tests\Skeleton;

use Foundation\Providers\ConsoleFoundationServiceProvider;
use Foundation\Providers\HttpFoundationServiceProvider;
use InvalidArgumentException;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Container\Exceptions\ContainerException;
use Maduser\Argon\Container\Exceptions\NotFoundException;
use Maduser\Argon\Contracts\Handler\AppHandlerInterface;
use Maduser\Argon\Http\Kernel;
use Maduser\Argon\Http\Message\ServerRequest;
use Maduser\Argon\Http\Message\Uri;
use Maduser\Argon\Support\Contracts\ErrorHandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Skeleton\Support\Providers\TestRoutingServiceProvider;
use Throwable;

abstract class ApplicationTestCase extends TestCase
{
    /**
     * @var array<non-empty-string, string|null>
     */
    private array $envBackup = [];

    #[\Override]
    protected function setUp(): void
    {
        $this->setEnv('APP_NAME', 'Argon App');
        $this->setEnv('APP_ENV', 'production');
        $this->setEnv('APP_DEBUG', 'false');
        $this->setEnv('APP_VERSION', '0.1.0');
    }

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

    /**
     * @throws ContainerException
     * @throws NotFoundException
     */
    protected function boot(string $runtime): ArgonContainer
    {
        $container = new ArgonContainer();

        $provider = match ($runtime) {
            'http' => HttpFoundationServiceProvider::class,
            'console' => ConsoleFoundationServiceProvider::class,
            default => throw new InvalidArgumentException(sprintf('Unsupported Argon runtime [%s].', $runtime)),
        };

        $container->register($provider);
        $container->boot();

        return $container;
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    protected function get(string $path, array $headers = []): ResponseInterface
    {
        return $this->handle('GET', $path, $headers);
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    protected function handle(string $method, string $path, array $headers = []): ResponseInterface
    {
        $container = $this->boot('http');
        $container->register(TestRoutingServiceProvider::class);
        $kernel = $container->get(AppHandlerInterface::class);
        $request = $this->request($method, $path, $headers);

        self::assertInstanceOf(Kernel::class, $kernel);

        try {
            return $kernel->process($request);
        } catch (Throwable $throwable) {
            $handler = $container->get(ErrorHandlerInterface::class);

            self::assertInstanceOf(ErrorHandlerInterface::class, $handler);

            return $handler->handle($throwable, $request);
        }
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    protected function request(string $method, string $path, array $headers = []): ServerRequestInterface
    {
        return new ServerRequest(
            method: $method,
            uri: new Uri($path),
            headers: $headers,
        );
    }

    /**
     * @param non-empty-string $key
     */
    protected function setEnv(string $key, string $value): void
    {
        if (!array_key_exists($key, $this->envBackup)) {
            $previous = $_ENV[$key] ?? null;
            $this->envBackup[$key] = is_string($previous) ? $previous : null;
        }

        $_ENV[$key] = $value;
    }
}
