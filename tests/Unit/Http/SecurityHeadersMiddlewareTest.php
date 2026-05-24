<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Foundation\Http\Middleware\SecurityHeadersMiddleware;
use Maduser\Argon\Http\Message\Response;
use Maduser\Argon\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SecurityHeadersMiddlewareTest extends TestCase
{
    public function testAddsDefaultSecurityHeaders(): void
    {
        $middleware = new SecurityHeadersMiddleware();
        $response = $middleware->process(new ServerRequest(), new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return Response::text('ok');
            }
        });

        self::assertSame('SAMEORIGIN', $response->getHeaderLine('X-Frame-Options'));
        self::assertSame('nosniff', $response->getHeaderLine('X-Content-Type-Options'));
        self::assertSame('strict-origin-when-cross-origin', $response->getHeaderLine('Referrer-Policy'));
    }
}
