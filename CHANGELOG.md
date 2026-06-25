# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- PHP 8.4+ modernization groundwork (Phase 0–1):
  - `ROADMAP.md` (10-phase ground-up rewrite plan) and `CLAUDE.md` contributor guide.
  - `AUTHORS` and `NOTICE` with `GPL-2.0-or-later` attribution (original authors preserved,
    Ramon Kaes added as 2026 modernization maintainer).
  - Composer manifest defining the target stack (Symfony 7.4 components, Twig 3,
    Doctrine DBAL 4 + Migrations, Monolog 3) with PSR-4 autoloading (`Q2A\` → `src/`).
  - Quality tooling: PHPStan (level max), php-cs-fixer (PSR-12), Rector (PHP 8.4),
    PHPUnit 11, and a GitHub Actions CI pipeline.
  - First strict-typed PSR-4 class `Q2A\Version` and a test pipeline smoke test.

### Changed

- Coding standard moved to PSR-12 / PER-CS (4 spaces) with `declare(strict_types=1)`
  for all new code; `CONTRIBUTING.md` updated accordingly.
- `LICENSE` aligned to GPL v2 text to match the project's "version 2 or later" grant.

[Unreleased]: https://github.com/q2a/question2answer/commits/feature/php84-modernization
