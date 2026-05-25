# Argon Skeleton

[![PHP](https://img.shields.io/badge/php-8.2+-blue)](https://www.php.net/)
[![Build](https://github.com/judus/argon-skeleton/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/judus/argon-skeleton/actions)
[![codecov](https://codecov.io/gh/judus/argon-skeleton/branch/main/graph/badge.svg)](https://codecov.io/gh/judus/argon-skeleton)
[![Psalm Level](https://shepherd.dev/github/judus/argon-skeleton/coverage.svg)](https://shepherd.dev/github/judus/argon-skeleton)
[![Latest Version](https://img.shields.io/packagist/v/maduser/argon-skeleton.svg)](https://packagist.org/packages/maduser/argon-skeleton)
[![Total Downloads](https://img.shields.io/packagist/dt/maduser/argon-skeleton.svg?color=blue)](https://packagist.org/packages/maduser/argon-skeleton)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

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

## Optional Packages

The skeleton only installs the core HTTP/CLI runtime. Optional integrations are
installed manually and registered explicitly from `AppServiceProvider` or a
dedicated application provider.

```bash
composer require maduser/argon-monolog
composer require maduser/argon-whoops
composer require maduser/argon-twig
composer require maduser/argon-eloquent
composer require maduser/argon-doctrine
composer require maduser/argon-phinx
composer require maduser/argon-filesystem
```

Available integration packages:

- `maduser/argon-monolog`: PSR-3 logging through Monolog.
- `maduser/argon-whoops`: local/debug exception rendering through Whoops.
- `maduser/argon-twig`: Twig environment and template path registration.
- `maduser/argon-eloquent`: Eloquent database manager and connection setup.
- `maduser/argon-doctrine`: Doctrine ORM entity manager setup.
- `maduser/argon-phinx`: Phinx migration commands for Argon Console.
- `maduser/argon-filesystem`: named Flysystem disks and default filesystem binding.

Provider registration stays explicit:

```php
final class AppServiceProvider extends AbstractServiceProvider
{
    #[\Override]
    public function register(ArgonContainer $container): void
    {
        $container->register([
            MonologServiceProvider::class,
            TwigServiceProvider::class,
            FilesystemServiceProvider::class,

            // Application-owned configuration for the integrations above.
            LoggingServiceProvider::class,
            TemplateServiceProvider::class,
            StorageServiceProvider::class,
        ]);
    }
}
```

Some packages require additional app-owned setup. For example, Twig needs
template paths, database packages need connections or mappings, Phinx needs
migration paths and environments, and filesystem disks need adapter instances.
Install third-party adapter packages directly when needed; the skeleton does
not auto-install optional dependencies or discover providers.

## Boundaries

This skeleton intentionally does not provide:

- Docker or deployment/runtime infrastructure
- cache, mail, queue, session, auth, or rate-limiting services
- database, migration, filesystem, template, or ORM wiring by default
- workflow integration
- route/config/provider file indirection
- automatic package discovery

Install optional integrations manually and wire them through explicit service
providers. The skeleton remains the minimal application starting point.
