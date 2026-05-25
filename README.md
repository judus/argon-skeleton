# Argon Skeleton

Minimal application skeleton for the Argon runtime stack.

This repository is intended for `composer create-project`. It is deliberately not named `argon-framework`: the framework-level runtime lives in the `maduser/argon-*` packages, while this project is the starting application layout.

## Create Project

```bash
composer create-project maduser/argon-skeleton my-app
```

## Local Usage

```bash
cp .env.example .env
composer install
composer serve
composer console
composer test
composer check
```

## Quality Gates

```bash
composer validate --strict
composer check
composer test:coverage
composer psalm
composer phpcs
```

## Shape

- `public/index.php` selects the HTTP runtime.
- `bin/console` selects the console runtime.
- `foundation/Providers/ConfigServiceProvider.php` loads `.env` and writes application parameters.
- `foundation/Providers/AppServiceProvider.php` is the application service registration point.
- `foundation/Providers/HttpFoundationServiceProvider.php` wires HTTP packages and default middleware.
- `foundation/Providers/ConsoleFoundationServiceProvider.php` wires Symfony Console through Argon.
- `foundation/Providers/LoggingServiceProvider.php` registers the default logger.
- `foundation/Providers/MiddlewareServiceProvider.php` registers application middleware.
- `foundation/Providers/ConsoleCommandServiceProvider.php` registers application console commands.
- `foundation/Providers/ErrorHandlingServiceProvider.php` wires application exception handling.
- `foundation/Providers/AppRoutingServiceProvider.php` declares application routes and route groups.
- The starter application ships only the `/` route; feature tests register their own test routes.
- `src/` is intentionally empty for application code.
- `tests/Skeleton/` contains starter contract tests for the shipped skeleton.
- `tests/Feature/` and `tests/Unit/` are reserved for application tests.

## Provider Order

Entrypoints register exactly one runtime foundation provider. Runtime providers register configuration first, set their runtime-specific parameters, and then register the provider graph in execution order.

For the HTTP runtime, order matters:

1. `ConfigServiceProvider` loads `.env` and writes application parameters.
2. `HttpFoundationServiceProvider` sets HTTP runtime parameters.
3. `ErrorHandlingServiceProvider` registers exception policies.
4. HTTP message, middleware, routing, and kernel providers are registered.
5. `AppServiceProvider` registers application services.
6. `MiddlewareServiceProvider` tags application middleware.
7. `AppRoutingServiceProvider` declares application routes and route groups.

For the console runtime, `ConfigServiceProvider` runs first, then `ConsoleFoundationServiceProvider` sets console parameters, registers Symfony Console, and registers application services and commands.

Optional integrations such as Monolog, Twig, Eloquent, Doctrine, or Workflow should live in explicit integration packages or application providers. The skeleton does not install them implicitly.
