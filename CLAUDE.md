# CLAUDE.md

Leitfaden für die Arbeit von Claude (und Menschen) an diesem Repository.
Dies ist ein **lebendes Dokument** — bei relevanten Änderungen aktuell halten,
insbesondere den Abschnitt „Aktueller Stand".

## Projekt

Modernisierung von **Question2Answer (Q2A)** — einer Q&A-Plattform — von der
prozeduralen Version 1.8.8 auf einen **PHP-8.4+-Stack nach Industriestandard 2026**.
Es handelt sich um einen **Ground-up Rewrite mit Feature-Parität**, nicht um ein
In-place-Patching. Detaillierte Schritte: siehe [ROADMAP.md](ROADMAP.md).

**Wichtig:** Keine Rückwärtskompatibilität zu bestehenden Plugins/Themes nötig.
Mitgelieferte Addons werden später angepasst (Phase 8).

## Grundsatzentscheidungen (verbindlich)

- **Architektur:** Ground-up Rewrite, Feature-Parität zu 1.8.8.
- **PHP:** 8.4+, `declare(strict_types=1)` in **jeder** Datei.
- **Code-Stil:** PSR-12 / PER-CS, **4 Spaces** (keine Tabs). CONTRIBUTING.md und
  das phpcs-Ruleset werden entsprechend angepasst.
- **Composer = nur lokales Build-Werkzeug:** Auf dem Laptop löst Composer den
  Abhängigkeitsbaum auf und befüllt `vendor/`. `composer.json` + `composer.lock` **und**
  `vendor/` werden **committet**. End-User laden hoch und es läuft — sie führen Composer **nie** aus.
  - Autoloading über den von Composer generierten **PSR-4**-Autoloader (Namespace `Q2A\`, committet).
- **DB:** Doctrine DBAL (Query-Builder, durchgängig prepared).
- **Templating/Themes:** Twig (Auto-Escaping → a11y/i18n/XSS). **Dritt-Themes bleiben möglich**:
  ein Theme ist ein Paket-Verzeichnis (`theme.json` + `templates/`-Overrides + `assets/` + optional
  `Theme.php`), das Core-Templates per Twig-Namespace (`@core/…`) überschreibt oder via
  `{% extends %}`/`{% block %}` erweitert. Plugin-Injektion über Hooks (ersetzt das alte „layer"-System);
  rein optische Anpassung über CSS Custom Properties / Design-Tokens.
- **i18n:** native `MessageFormatter` (ICU MessageFormat) aus `ext-intl` — keine Bibliothek.
  `ext-intl` wird damit zur dokumentierten Installationsvoraussetzung.
- **a11y:** WCAG 2.2 AA als Mindeststandard.
- **Branding/Lizenz:** Q2A-Name bleibt; Original-Credits bleiben; „Ramon Kaes" ergänzt; GPL-2.0-or-later.

## Empfohlener Stack

- **Symfony 7.x-Komponenten** (HttpFoundation, Routing, DependencyInjection, Console) als Fundament.
- **Twig** (Templating), **Doctrine DBAL** (DB).
- HTML-Sanitizing: htmLawed (bereits vorhanden) bzw. HTML Purifier.
- Mail: PHPMailer, auf **eine** Version konsolidieren (via Composer).
- Logging: Monolog (PSR-3).
- i18n: native `MessageFormatter` (ICU) aus `ext-intl`.

> *Vor Phase 2 final bestätigen.* Da Composer lokal den Abhängigkeitsbaum auflöst und `vendor/`
> committet wird, ist das Vendoring trivial — kein Hand-Pflegen von Abhängigkeiten nötig.

## Ziel-Verzeichnislayout (im Aufbau)

```
public/        # Front Controller (index.php), Assets
src/           # PSR-4: Namespace Q2A\  → src/ (eigener Autoloader)
templates/     # Twig
config/        # DI, Routing, Konfiguration
translations/  # i18n-Ressourcen
migrations/    # Doctrine Migrations
vendor/        # Composer-Abhängigkeiten (committet) inkl. Autoloader
qa-tests/      # PHPUnit-Suite (committet, von End-Usern löschbar)
```

Die Legacy-Bäume `qa-include/`, `qa-theme/`, `qa-plugin/`, `qa-lang/` bleiben
**parallel** bestehen, bis Parität erreicht ist (Entfernung in Phase 9).

## Coding-Standard

- `declare(strict_types=1);` direkt nach dem Datei-Header.
- PSR-12, 4 Spaces, kein schließendes `?>`, UTF-8 ohne BOM, kein Trailing Whitespace.
- Strikte Typen überall; **kein implizites Nullable** — `?string $x = null`, nie `string $x = null`.
- PHPStan Level max und php-cs-fixer (PSR-12) müssen grün sein, bevor eine Aufgabe „fertig" ist
  (`composer check` führt cs + stan + test gemeinsam aus).

### PHP-8.4-Features — bewusst einsetzen

**Verwenden:**
- Asymmetrische Sichtbarkeit (`public private(set)`) für Entities/Value Objects.
- `array_find` / `array_any` / `array_all` / `array_find_key`.
- `new X()->method()` ohne Klammern (Stil, niedrige Priorität).
- `mb_trim` / `mb_ucfirst` / `mb_lcfirst` für Multibyte — **aber Trimmen ≠ Sanitizing**.
- Enums, `readonly`-Klassen/Properties für DTOs, typisierte Konstanten, named args, First-class callables.
- `password_hash` mit Bcrypt-Kosten 12 / Argon2id + `password_needs_rehash()` beim Login.

**Mit Vorbehalt / nicht dogmatisch:**
- Property Hooks: nur für neuen OOP-Code, selektiv (nicht mit `readonly` kombinierbar, leichter Overhead).
  Bestehende Getter nicht pauschal ersetzen.

**Nicht tun:**
- DOM-HTML5 (`\Dom\HTMLDocument`) **nicht** als Ersatz für HTML-Sanitizing verwenden
  (das ist Parsen, kein Sicherheits-Filtern). Sanitizing bleibt bei htmLawed / HTML Purifier.
- `mb_trim` nicht mit Eingabe-Sanitisierung verwechseln.
- End-User nie zwingen, Composer auszuführen — `vendor/` ist immer committet/im Release enthalten.

## Lizenz & Attribution (GPL-konform)

- Lizenz: **GPL-2.0-or-later** (SPDX). Original-Urheberrecht von **Gideon Greenspan und
  contributors** bleibt erhalten — nichts entfernen.
- „**Ramon Kaes**" (mit *ae*) wird als Maintainer der Modernisierung ergänzt, GitHub-Standard:
  Eintrag in `AUTHORS`/`NOTICE`, Co-Authored-By in Commits, Ergänzung im Datei-Header.

Datei-Header-Vorlage für **neue** Dateien:

```php
<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: <pfad/zur/datei.php>
    Description: <kurzbeschreibung>

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);
```

> Hinweis: php-cs-fixer (`@PSR12`) erwartet eine Leerzeile zwischen `<?php` und dem Header-Kommentar.

## Tests

- Test-Suite liegt in **[qa-tests/](qa-tests/)** (bestehender Ordner, **committet**;
  End-User können ihn gefahrlos löschen — siehe dessen README).
- Framework: PHPUnit 11/12 mit Attributen (`#[Test]`, `#[Group]`), nicht Annotations.
- Bestehende Cases unter [qa-tests/tests/](qa-tests/tests/) als Vorlage modernisieren.
- Jede neue Klasse braucht Unit-Tests.

## Arbeitsweise für Claude

- Sprache mit dem Maintainer: **Deutsch**.
- Roadmap **der Reihe nach** abarbeiten; nach jeder erledigten Aufgabe „Aktueller Stand" updaten.
- Keine Aufgabe als erledigt melden, ohne dass php-cs-fixer + PHPStan + relevante Tests grün sind (`composer check`).
- Bei Architektur-Weichen, die mehrere Phasen prägen, Rücksprache halten statt raten.
- Commits/PRs nur auf Aufforderung; nicht auf den Default-Branch committen ohne Branch.

## Ist-Zustand der Altanwendung (Referenz)

- Q2A **1.8.8**, rein prozedural, `qa-*`-Dateipräfixe; wenige Klassen unter
  [qa-include/Q2A/](qa-include/Q2A/) (Faux-Namespace PSR-0, eigener Autoloader in
  [qa-include/qa-base.php](qa-include/qa-base.php)).
- DB: **mysqli** mit eigenem Query-Building ([qa-include/qa-db.php](qa-include/qa-db.php)).
- Bisher kein Composer; künftig Composer als **lokales Build-Werkzeug**, `vendor/` committet. Min-PHP deklariert 5.1.6.
- Zwei gebündelte PHPMailer-Versionen + `password_compat`-Shim (Altlasten → konsolidieren/entfernen).

## Aktueller Stand

- **Phase:** 2 (Architektur-Grundgerüst) — in Arbeit. Erledigt: Front Controller, Router
  (Symfony Routing, Controller-Interface, 404), DI-Container (Controller als Services). Offen: `.env`, Twig.
- **Stack installiert:** Symfony 7.4-Komponenten (HttpFoundation, Routing, DI, Config, Console,
  EventDispatcher, Cache, Dotenv), Twig 3, Doctrine DBAL 4 + Migrations, Monolog 3; dev: PHPUnit 11.5,
  PHPStan 2, Rector 2, php-cs-fixer 3. `vendor/` + `composer.lock` committet.
- **Pipeline grün (lokal verifiziert):** `composer cs` (PSR-12), `composer stan` (level max),
  `composer test` (PHPUnit), `vendor/bin/rector --dry-run` laufen fehlerfrei. CI unter
  [.github/workflows/ci.yml](.github/workflows/ci.yml).
- **Erledigt (Phase 0–1):** Planung (ROADMAP/CLAUDE); Attribution (AUTHORS/NOTICE, SPDX);
  `LICENSE` auf GPLv2-Text gesetzt; `composer.json`/`.lock` + `vendor/`; `src/Version.php` (PSR-4);
  `phpstan.neon`; `.php-cs-fixer.dist.php`; `rector.php`; `phpunit.xml.dist` + Smoke-Test;
  CONTRIBUTING.md (PSR-12 + Tooling); `SECURITY.md`; `CHANGELOG.md`; CI-Workflow.
- **Nächster Schritt:** **Phase 2** — Front Controller (`public/index.php`), Routing, DI-Container,
  `.env`-Konfiguration, Doctrine-DBAL-Connection-Factory, Twig-Integration mit Basis-Layout.
