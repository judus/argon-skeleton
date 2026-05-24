<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Maduser\Argon\Container\ArgonContainer;
use Maduser\Argon\Contracts\Handler\AppHandlerInterface;
use Maduser\Argon\Http\Kernel;
use Maduser\Argon\Http\Message\ServerRequest;
use Maduser\Argon\Http\Message\Uri;
use Maduser\Argon\Support\Contracts\ErrorHandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

abstract class ApplicationTestCase extends TestCase
{
    protected function boot(string $runtime): ArgonContainer
    {
        $container = new ArgonContainer();
        $this->bootstrap()($runtime)($container);
        $container->boot();

        return $container;
    }

    protected function get(string $path, array $headers = []): ResponseInterface
    {
        return $this->handle('GET', $path, $headers);
    }

    protected function handle(string $method, string $path, array $headers = []): ResponseInterface
    {
        $container = $this->boot('http');
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
     * @return Closure(string): Closure(ArgonContainer): void
     */
    private function bootstrap(): Closure
    {
        /** @var Closure(string): Closure(ArgonContainer): void $bootstrap */
        $bootstrap = require dirname(__DIR__) . '/bootstrap/app.php';

        return $bootstrap;
    }
}
