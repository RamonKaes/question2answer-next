# How to contribute

This repository tracks the **PHP 8.4+ modernization** of [Question2Answer](https://www.question2answer.org/).
Bug reports and pull requests are welcome, provided they follow these guidelines.


## Bug reports (issues)

If you find a bug, please [submit an issue](https://github.com/q2a/question2answer/issues).
Be as descriptive as possible: include exactly what you did, what you expected, and what
happened instead. Include your PHP and MySQL/MariaDB versions. Check for similar issues first.

For security issues, **do not open a public issue** — see [SECURITY.md](SECURITY.md).


## Pull requests

Fork the repo, create a topic branch, make your changes, then open a pull request against the
active development branch. Keep commits small, logical and clearly described.

A pull request is expected to pass the quality gate before review (see below).


## Coding style

New code follows **PSR-12 / PER-CS**. The legacy `qa-*` trees keep the old tab-based style until
they are removed during the rewrite; do not reformat them in unrelated changes.

- Every PHP file starts with `<?php`, followed by a blank line and the license header block.
- `declare(strict_types=1);` after the header in every PHP file.
- **4 spaces** for indentation (no tabs).
- UTF-8 without BOM; no trailing whitespace; omit the closing `?>` tag.
- Strict typing everywhere; **no implicit nullable** — write `?string $x = null`, never `string $x = null`.
- Code is namespaced under `Q2A\` and autoloaded via PSR-4 from `src/`.
- Public methods and classes use DocBlock comments where they add value.

The authoritative rules are encoded in the tooling — when in doubt, run it (see below).


## Tooling (local, via Composer)

Dependencies are managed with Composer **on your machine only**; the committed `vendor/`
directory (production dependencies only, ~14 MB) means end users never need to run Composer.
Dev tools (PHPUnit, PHPStan, Rector, php-cs-fixer) are isolated via
[`bamarni/composer-bin-plugin`](https://github.com/bamarni/composer-bin-plugin) into
`vendor-bin/` (gitignored) and are installed automatically by `composer install`.

```sh
composer install        # install production deps + dev tools (into vendor-bin/)
composer cs             # check coding standard (php-cs-fixer, dry-run)
composer cs:fix         # auto-fix coding standard
composer stan           # static analysis (PHPStan, level max)
composer rector         # automated refactoring / upgrade rules (dry-run)
composer test           # run the test suite (PHPUnit)
composer check          # cs + stan + test (the full quality gate)
```

All of the above must pass before a change is considered done. The same checks run in CI on
every push and pull request.


## License

By contributing you agree that your contributions are licensed under **GPL-2.0-or-later**
(see [LICENSE](LICENSE)). New files carry the standard header; see [CLAUDE.md](CLAUDE.md).
