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
- `foundation/Providers/AppServiceProvider.php` is the application service registration point.
- `foundation/Providers/HttpFoundationServiceProvider.php` wires HTTP packages and default middleware.
- `foundation/Providers/ConsoleFoundationServiceProvider.php` wires Symfony Console through Argon.
- `foundation/Providers/LoggingServiceProvider.php` registers the default logger.
- `foundation/Providers/MiddlewareServiceProvider.php` registers application middleware.
- `foundation/Providers/ConsoleCommandServiceProvider.php` registers application console commands.
- `foundation/Providers/ErrorHandlingServiceProvider.php` wires application exception handling.
- `foundation/Providers/AppRoutingServiceProvider.php` loads `routes/web.php`.
- `src/` is intentionally empty for application code.
- `tests/ApplicationTestCase.php` boots the real application container for integration tests.
- `tests/Feature/` contains HTTP, console, and container-context integration examples.
- `tests/Unit/` is available for isolated tests when that is the better fit.

## Provider Order

Entrypoints register exactly one runtime foundation provider. Runtime providers load their own environment/config and then register the provider graph in execution order.

For the HTTP runtime, order matters:

1. `HttpFoundationServiceProvider` loads environment/config and sets HTTP runtime parameters.
2. `ErrorHandlingServiceProvider` registers exception policies.
3. HTTP message, middleware, routing, and kernel providers are registered.
4. `AppServiceProvider` registers application services.
5. `MiddlewareServiceProvider` tags application middleware.
6. `AppRoutingServiceProvider` loads application routes into the router.

For the console runtime, `ConsoleFoundationServiceProvider` loads environment/config, registers Symfony Console, then registers application services and commands.

Optional integrations such as Monolog, Twig, Eloquent, Doctrine, or Workflow should live in explicit integration packages or application providers. The skeleton does not install them implicitly.
