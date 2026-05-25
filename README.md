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
- `foundation/Providers/ApplicationFoundationServiceProvider.php` loads environment, config, and application providers.
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

Entrypoints register exactly one runtime foundation provider. Runtime providers register `ApplicationFoundationServiceProvider` first so `.env`, `config/app.php`, and application providers from `config/providers.php` are available before runtime-specific services are wired.

For the HTTP runtime, order matters:

1. `ApplicationFoundationServiceProvider` loads environment/config and registers application providers.
2. `ErrorHandlingServiceProvider` registers exception policies.
3. HTTP message, middleware, routing, and kernel providers are registered.
4. Default HTTP middleware is tagged.
5. `AppRoutingServiceProvider` loads application routes into the router.

For the console runtime, `ApplicationFoundationServiceProvider` runs first, then `ConsoleFoundationServiceProvider` registers Symfony Console and tagged commands.

Optional integrations such as Monolog, Twig, Eloquent, Doctrine, or Workflow should live in explicit integration packages or application providers. The skeleton does not install them implicitly.
