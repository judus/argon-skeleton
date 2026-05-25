<?php

declare(strict_types=1);

namespace Tests\Skeleton\Feature;

use Tests\Skeleton\ApplicationTestCase;

final class HttpTest extends ApplicationTestCase
{
    public function testRootRouteReturnsWelcomePageThroughApplicationKernel(): void
    {
        $response = $this->get('/');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/html; charset=UTF-8', $response->getHeaderLine('Content-Type'));
        self::assertStringContainsString('Your application skeleton is running.', (string) $response->getBody());
    }

    public function testHealthRouteReturnsJsonThroughApplicationKernel(): void
    {
        $response = $this->get('/health');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertSame('{"status":"ok","runtime":"argon"}', (string) $response->getBody());
    }

    public function testDefaultWebMiddlewareRunsThroughApplicationKernel(): void
    {
        $response = $this->get('/health');

        self::assertSame('SAMEORIGIN', $response->getHeaderLine('X-Frame-Options'));
        self::assertSame('nosniff', $response->getHeaderLine('X-Content-Type-Options'));
        self::assertSame('strict-origin-when-cross-origin', $response->getHeaderLine('Referrer-Policy'));
    }

    public function testMissingRouteUsesApplicationExceptionPolicy(): void
    {
        $response = $this->get('/missing');

        self::assertSame(404, $response->getStatusCode());
        self::assertStringContainsString('Request error', (string) $response->getBody());
        self::assertStringContainsString('No route matched: GET /missing', (string) $response->getBody());
    }

    public function testMissingRouteCanRenderJsonErrorResponse(): void
    {
        $response = $this->get('/missing', ['Accept' => 'application/json']);

        self::assertSame(404, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertStringContainsString('"message":"No route matched: GET \\/missing"', (string) $response->getBody());
        self::assertStringContainsString('"status":404', (string) $response->getBody());
    }

    public function testRuntimeExceptionUsesApplicationExceptionPolicy(): void
    {
        $response = $this->get('/error');

        self::assertSame(503, $response->getStatusCode());
        self::assertStringContainsString('Application error', (string) $response->getBody());
        self::assertStringContainsString(
            'This exception was rendered by the application exception policy.',
            (string) $response->getBody(),
        );
    }

    public function testRuntimeExceptionCanRenderJsonErrorResponse(): void
    {
        $response = $this->get('/error', ['Accept' => 'application/json']);

        self::assertSame(503, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertStringContainsString(
            '"message":"This exception was rendered by the application exception policy."',
            (string) $response->getBody(),
        );
        self::assertStringContainsString('"status":503', (string) $response->getBody());
    }
}
