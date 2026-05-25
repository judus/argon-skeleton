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

- `bootstrap/app.php` builds the runtime-specific container bootstrap.
- `foundation/Providers/HttpFoundationServiceProvider.php` wires HTTP packages and default middleware.
- `foundation/Providers/ConsoleFoundationServiceProvider.php` wires Symfony Console through Argon.
- `foundation/Providers/ErrorHandlingServiceProvider.php` wires application exception handling.
- `foundation/Providers/AppRoutingServiceProvider.php` loads `routes/web.php`.
- `config/providers.php` is the explicit list for application service providers.
- `src/` is intentionally empty for application code.
- `tests/ApplicationTestCase.php` boots the real application container for integration tests.
- `tests/Feature/` contains HTTP, console, and container-context integration examples.
- `tests/Unit/` is available for isolated tests when that is the better fit.

## Provider Order

Application providers from `config/providers.php` are registered first. Runtime-specific foundation providers are registered afterward.

For the HTTP runtime, order matters:

1. `ErrorHandlingServiceProvider` registers exception policies.
2. `HttpFoundationServiceProvider` registers HTTP messages, middleware, routing services, the HTTP kernel, and default middleware.
3. `AppRoutingServiceProvider` loads application routes into the router.

For the console runtime, `ConsoleFoundationServiceProvider` registers Symfony Console and tagged commands.

Optional integrations such as Monolog, Twig, Eloquent, Doctrine, or Workflow should live in explicit integration packages or application providers. The skeleton does not install them implicitly.
