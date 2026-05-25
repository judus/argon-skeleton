# Changelog

## [Unreleased]

## [1.0.1] - 2026-05-25

- Fixed skeleton contract tests so they do not depend on whether a user has copied `.env.example` to `.env`.

## [1.0.0] - 2026-05-25

- Added the initial Argon application skeleton for Composer `create-project`.
- Wired explicit HTTP and console runtime foundation providers.
- Added class-first providers for configuration, logging, error handling, middleware, routing, app services, and console commands.
- Added a minimal root route and a default security headers middleware.
- Added strict quality gates for PHPUnit, Psalm, PHPCS/Slevomat, and Composer validation.
- Added skeleton contract tests with dedicated `tests/Skeleton` coverage.
