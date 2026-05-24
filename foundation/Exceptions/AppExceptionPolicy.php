<?php

declare(strict_types=1);

namespace Foundation\Exceptions;

use Maduser\Argon\Error\Contracts\ExceptionPolicyInterface;
use Maduser\Argon\Error\Contracts\ExceptionPolicyRegistryInterface;
use Maduser\Argon\Error\Contracts\HttpExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class AppExceptionPolicy implements ExceptionPolicyInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function register(ExceptionPolicyRegistryInterface $exceptions): void
    {
        $exceptions->report(Throwable::class, function (Throwable $exception): void {
            $this->logger->error('Unhandled exception.', [
                'exception' => $exception,
            ]);
        });

        $exceptions->render(
            Throwable::class,
            fn(Throwable $exception, ServerRequestInterface $request): ResponseInterface => $this->render(
                $exception,
                $request,
            ),
        );
    }

    private function render(Throwable $exception, ServerRequestInterface $request): ResponseInterface
    {
        $status = $this->statusCode($exception);

        if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            return $this->json($exception, $status);
        }

        return $this->html($exception, $status);
    }

    private function statusCode(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();

            return $status >= 400 && $status <= 599 ? $status : 500;
        }

        $code = $exception->getCode();

        return $code >= 400 && $code <= 599 ? $code : 500;
    }

    private function json(Throwable $exception, int $status): ResponseInterface
    {
        $payload = [
            'error' => $exception::class,
            'message' => $exception->getMessage(),
            'status' => $status,
        ];

        return $this->responseFactory
            ->createResponse($status)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode($payload, JSON_THROW_ON_ERROR)));
    }

    private function html(Throwable $exception, int $status): ResponseInterface
    {
        $title = $status >= 500 ? 'Application error' : 'Request error';
        $message = htmlspecialchars($exception->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $this->responseFactory
            ->createResponse($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody($this->streamFactory->createStream(<<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title}</title>
</head>
<body>
    <main>
        <h1>{$title}</h1>
        <p>{$message}</p>
    </main>
</body>
</html>
HTML));
    }
}
