# Argon Skeleton

Minimal application skeleton for the Argon runtime stack.

This is a `composer create-project` starting point. Framework/runtime behavior
lives in the `maduser/argon-*` packages; this repository contains the default
application layout and wiring.

## Create Project

```bash
composer create-project maduser/argon-skeleton my-app
cd my-app
cp .env.example .env
composer install
```

## Commands

```bash
composer serve
composer console
composer test
composer check
```

`composer serve` runs PHP's built-in server through `server.php`. It is a local
development command, not a production runtime.

## Quality Gates

```bash
composer validate --strict
composer check
composer test:coverage
composer psalm
composer phpcs
```

`composer check` runs syntax checks, PHPUnit, Psalm level 1, and PHPCS/Slevomat.

## Layout

- `public/index.php` selects the HTTP runtime.
- `bin/console` selects the console runtime.
- `server.php` adapts PHP's built-in server to `public/index.php`.
- `foundation/` contains the default application wiring.
- `src/` is intentionally empty for application code.
- `storage/cache/` is available for writable application cache files.
- `tests/Skeleton/` contains starter contract tests for the shipped skeleton.
- `tests/Feature/` and `tests/Unit/` are reserved for application tests.

## Providers

Runtime entrypoints register one foundation provider:

```php
Argon::prophecy(static function (ArgonContainer $container): void {
    $container->register(HttpFoundationServiceProvider::class);
});
```

HTTP provider order:

1. `ConfigServiceProvider`
2. HTTP runtime parameter setup
3. `LoggingServiceProvider`
4. `ErrorHandlingServiceProvider`
5. `HttpMessageServiceProvider`
6. `MiddlewarePipelineServiceProvider`
7. `RouteServiceProvider`
8. `HttpKernelServiceProvider`
9. `AppServiceProvider`
10. `MiddlewareServiceProvider`
11. `AppRoutingServiceProvider`

Console provider order:

1. `ConfigServiceProvider`
2. Console runtime parameter setup
3. `LoggingServiceProvider`
4. `ConsoleServiceProvider`
5. `AppServiceProvider`
6. `ConsoleCommandServiceProvider`

## Application Hooks

- Register services in `foundation/Providers/AppServiceProvider.php`.
- Register HTTP middleware in `foundation/Providers/MiddlewareServiceProvider.php`.
- Register routes in `foundation/Providers/AppRoutingServiceProvider.php`.
- Register console commands in `foundation/Providers/ConsoleCommandServiceProvider.php`.
- Adjust environment-derived parameters in `foundation/Providers/ConfigServiceProvider.php`.
- Adjust exception reporting/rendering in `foundation/Exceptions/AppExceptionPolicy.php`.

The starter application ships only the `/` route.

## Middleware

The skeleton ships one default HTTP middleware:

- `SecurityHeadersMiddleware`

It adds:

- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`

It is registered in `MiddlewareServiceProvider` under the `web` group. Remove or
replace it there if the application needs different headers.

The skeleton does not ship body parsing, sessions, CSRF, CORS, auth, rate
limiting, request logging, trusted proxy handling, or response formatting
middleware. Add those explicitly when the application needs them.

## Configuration

`.env` is loaded by `ConfigServiceProvider`.

Supported defaults:

- `APP_NAME`
- `APP_ENV`
- `APP_DEBUG`
- `APP_VERSION`

The provider writes typed parameters into the container parameter store:

- `app.name`
- `app.env`
- `app.debug`
- `app.version`

HTTP adds `kernel.shouldExit`. Console adds `console.name` and
`console.version`.

## Tests

The shipped tests under `tests/Skeleton/` verify the skeleton contract:

- container parameters
- HTTP dispatch
- root route
- middleware execution
- exception handling
- console registration

Add application tests under:

- `tests/Feature/`
- `tests/Unit/`

## Boundaries

This skeleton intentionally does not provide:

- Docker or deployment/runtime infrastructure
- database/cache/mail services
- view/template integration
- ORM integration
- workflow integration
- route/config/provider file indirection
- automatic package discovery

Install and wire additional packages through explicit service providers.
