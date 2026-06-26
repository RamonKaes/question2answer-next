Running Q2A's unit tests
========================

This folder holds the automated test suite. It is **not** required to run Q2A —
end users can safely delete the `qa-tests/` folder.

Modernized suite (PHP 8.4+)
---------------------------

Tests for the modernized code base live under `qa-tests/unit/` and use PHPUnit 11
with attributes (`#[CoversClass]`, …). They are wired up through the project's
`phpunit.xml.dist` and the Composer-managed tooling (Composer is a local
development tool only — end users never run it):

```sh
composer install   # once, on your development machine
composer test      # run the test suite (PHPUnit)
composer check     # coding standard (php-cs-fixer) + static analysis (PHPStan) + tests
```

Tests autoload through Composer (`vendor/autoload.php`); the namespace
`Q2A\Tests\` maps to `qa-tests/unit/`.

Legacy suite
------------

The original procedural tests under `qa-tests/tests/` target the legacy Q2A 1.8.8
code together with their own bootstrap (`autoload.php`, `Q2A_TestsSetup.php`, …).
They are kept for reference and are **not** part of `phpunit.xml.dist`. They will
be replaced by tests for the rewritten `src/` code and removed with the rest of
the legacy trees in Phase 9 (see [ROADMAP.md](../ROADMAP.md)).
