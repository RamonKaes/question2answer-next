# CLAUDE.md

Guidance for working on this repository (for Claude and humans). This is a **living
document** — keep it current when decisions change. Progress and status are tracked in
[ROADMAP.md](ROADMAP.md), not here.

## Project

Modernization of **Question2Answer (Q2A)** — a Q&A platform — from the procedural 1.8.8
release to a **PHP 8.4+ stack (2026 industry standard)**. This is a **ground-up rewrite with
feature parity**, not in-place patching. Step-by-step plan: see [ROADMAP.md](ROADMAP.md).

No backward compatibility with existing plugins/themes is required; bundled add-ons are
ported later (Phase 8).

## Binding decisions

- **Architecture:** ground-up rewrite, feature parity with 1.8.8.
- **PHP:** 8.4+, `declare(strict_types=1)` in **every** file.
- **Code style:** PSR-12 / PER-CS, **4 spaces** (no tabs), enforced by php-cs-fixer.
- **Composer = local build tool only:** Composer resolves dependencies on the developer
  machine and fills `vendor/`. `composer.json` + `composer.lock` **and** `vendor/` are
  **committed**. End users upload files and it runs — they never run Composer.
  - Autoloading via Composer's generated **PSR-4** autoloader (namespace `Q2A\`, committed).
  - Dev tools are isolated via `bamarni/composer-bin-plugin` in `vendor-bin/` (gitignored);
    the shipped `vendor/` is **production only** (~14 MB).
- **DB:** Doctrine DBAL (query builder, prepared throughout).
- **Templating/Themes:** Twig (auto-escaping → a11y/i18n/XSS). **Third-party themes stay
  possible**: a theme is a package directory (`theme.json` + `templates/` overrides + `assets/`
  + optional `Theme.php`) that overrides core templates via the `@core/…` Twig namespace or
  extends them with `{% extends %}`/`{% block %}`. Plugin injection via hooks (replaces the old
  "layer" system); purely visual changes via CSS custom properties / design tokens.
- **i18n:** native `MessageFormatter` (ICU MessageFormat) from `ext-intl` — no library.
  `ext-intl` is therefore a documented install requirement.
- **a11y:** WCAG 2.2 AA as the minimum.
- **Branding/License:** Q2A name stays; original credits stay; "Ramon Kaes" added;
  GPL-2.0-or-later.

## Recommended stack

- **Symfony 7.x components** (HttpFoundation, Routing, DependencyInjection, Console,
  EventDispatcher, Cache, Dotenv).
- **Twig** (templating), **Doctrine DBAL** (DB), **Monolog** (PSR-3 logging).
- HTML sanitizing: htmLawed (already present) or HTML Purifier.
- Mail: PHPMailer, consolidated to one version (via Composer).

## Target directory layout (in progress)

```
public/        # Front controller (index.php), assets
src/           # PSR-4: namespace Q2A\ → src/
templates/     # Twig
config/        # DI, routing, configuration
translations/  # i18n resources
migrations/    # Doctrine migrations
vendor/        # Composer deps (committed, production only) incl. autoloader
vendor-bin/    # Dev tools (gitignored) via composer-bin-plugin
qa-tests/      # PHPUnit suite (committed, deletable by end users)
```

Legacy trees `qa-include/`, `qa-theme/`, `qa-plugin/`, `qa-lang/` stay in **parallel** until
parity is reached (removed in Phase 9).

## Coding standard

- `declare(strict_types=1);` right after the file header.
- PSR-12, 4 spaces, no closing `?>`, UTF-8 without BOM, no trailing whitespace.
- Strict types everywhere; **no implicit nullable** — `?string $x = null`, never `string $x = null`.
- PHPStan level max and php-cs-fixer (PSR-12) must pass before a task is "done"
  (`composer check` runs cs + stan + test).

### PHP 8.4 features — use deliberately

**Use:**
- Asymmetric visibility (`public private(set)`) for entities/value objects.
- `array_find` / `array_any` / `array_all` / `array_find_key`.
- `new X()->method()` without parentheses (style, low priority).
- `mb_trim` / `mb_ucfirst` / `mb_lcfirst` for multibyte — **but trimming ≠ sanitizing**.
- Enums, `readonly` classes/properties for DTOs, typed constants, named args, first-class callables.
- `password_hash` with bcrypt cost 12 / Argon2id + `password_needs_rehash()` on login.

**With caution / not dogmatic:**
- Property hooks: only for new OOP code, selectively (not combinable with `readonly`, slight
  overhead). Don't replace existing getters wholesale.

**Don't:**
- Don't use DOM-HTML5 (`\Dom\HTMLDocument`) as a replacement for HTML sanitizing (that's parsing,
  not security filtering). Sanitizing stays with htmLawed / HTML Purifier.
- Don't confuse `mb_trim` with input sanitization.
- Never force end users to run Composer — `vendor/` is always committed / in the release.

## License & attribution (GPL-compliant)

- License: **GPL-2.0-or-later** (SPDX). The original copyright of **Gideon Greenspan and
  contributors** stays — remove nothing.
- "**Ramon Kaes**" (with *ae*) is added as the modernization maintainer (GitHub standard):
  entry in `AUTHORS`/`NOTICE`, Co-Authored-By in commits, a line in the file header.

Header template for **new** files:

```php
<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: <path/to/file.php>
    Description: <short description>

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);
```

> php-cs-fixer (`@PSR12`) expects a blank line between `<?php` and the header comment.

## Tests

- The suite lives in [qa-tests/](qa-tests/) (committed; end users can safely delete it). New
  tests go under `qa-tests/unit/` (namespace `Q2A\Tests\`).
- PHPUnit 11/12 with attributes (`#[Test]`, `#[CoversClass]`), not annotations.
- Every new class needs unit tests.

## Working method for Claude

- **Communicate with the maintainer in German.**
- Work through the roadmap in order; track progress in [ROADMAP.md](ROADMAP.md).
- Don't report a task as done unless php-cs-fixer + PHPStan + relevant tests pass (`composer check`).
- For architectural forks that shape multiple phases, ask rather than guess.
- Commits/PRs only when asked; never commit to the default branch without a branch.

## Legacy app (reference)

- Q2A **1.8.8**, purely procedural, `qa-*` file prefixes; a few classes under
  [qa-include/Q2A/](qa-include/Q2A/) (faux-namespace PSR-0, own autoloader in
  [qa-include/qa-base.php](qa-include/qa-base.php)).
- DB: **mysqli** with hand-rolled query building ([qa-include/qa-db.php](qa-include/qa-db.php)).
- Two bundled PHPMailer versions + a `password_compat` shim (legacy → consolidate/remove).
