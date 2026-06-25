# Roadmap — Question2Answer Modernisierung (PHP 8.4+, Industriestandard 2026)

> Ziel: Ground-up Rewrite von Q2A 1.8.8 auf einen modernen, streng typisierten,
> PSR-konformen PHP-8.4-Stack mit Feature-Parität, voller a11y- (WCAG 2.2 AA) und
> i18n-Abdeckung, automatisierter Qualitätssicherung und sauberer GPL-Konformität.

Diese Roadmap wird **der Reihe nach** abgearbeitet. Jede Phase hat eine
*Definition of Done* (DoD). Eine Phase gilt erst als fertig, wenn ihre DoD grün ist
(Tests + Static Analysis + Linter laufen durch). Fortschritt wird in
[CLAUDE.md](CLAUDE.md) unter „Aktueller Stand" gepflegt.

## Grundsatzentscheidungen (bestätigt)

| Thema | Entscheidung |
| --- | --- |
| Architektur | Ground-up Rewrite mit Feature-Parität |
| Ziel-PHP | 8.4+ (`declare(strict_types=1)` überall) |
| Code-Stil | PSR-12 / PER-CS, 4 Spaces (CONTRIBUTING.md + phpcs werden angepasst) |
| Composer | Nur **lokales Build-Werkzeug**; `composer.json`/`.lock` + `vendor/` committet; End-User führen Composer nie aus |
| DB-Layer | Doctrine DBAL (Query-Builder, durchgängig prepared) |
| Branding/Lizenz | Q2A-Name bleibt; Original-Credits bleiben erhalten, „Ramon Kaes" wird ergänzt; GPL-2.0-or-later |
| Templating/Themes | Twig; Dritt-Themes als Paket-Verzeichnis mit Template-Overrides (`{% extends %}`/Blocks) + Hooks + Design-Tokens |
| i18n | native `MessageFormatter` (ICU) aus `ext-intl`; bestehende Sprachdaten migriert |
| Tests | PHPUnit 11/12 in `qa-tests/` (committet, von End-Usern löschbar) |

---

## Phase 0 — Fundament & Entscheidungen

- [x] **Stack final bestätigt**: Symfony 7.4-Komponenten (HttpFoundation, Routing,
      DependencyInjection, Config, Console, EventDispatcher, Cache, Dotenv) + Twig + Doctrine DBAL + Monolog.
- [x] Ziel-Verzeichnislayout festgelegt (`src/`, `public/`, `templates/`, `config/`,
      `translations/`, `migrations/`, `vendor/`) und Tests in `qa-tests/`.
      Legacy-`qa-*`-Bäume bleiben vorerst parallel bestehen und werden erst in Phase 8/9 entfernt.
- [x] **Build-/Release-Prozess** festgelegt: `composer install` lokal, `vendor/` + `composer.lock` committet;
      End-User führen Composer nie aus.
- [x] **Lizenz-Inkonsistenz geklärt**: auf `GPL-2.0-or-later` (SPDX) standardisiert; `LICENSE` auf den
      kanonischen GPLv2-Text gesetzt (passend zum „version 2 or later"-Grant); `AUTHORS`/`NOTICE` angelegt
      (Gideon Greenspan & contributors + Ramon Kaes).
- [x] Einheitlicher Datei-Header definiert (siehe CLAUDE.md → „Lizenz & Attribution").

**DoD:** Stack & Layout dokumentiert in CLAUDE.md, Lizenzlage geklärt, `AUTHORS`/`NOTICE` existieren.

## Phase 1 — Tooling & Qualitätssicherung

- [x] `composer.json` + `composer.lock` angelegt (PHP `>=8.4`, PSR-4 `Q2A\` → `src/`);
      Abhängigkeiten via `composer install` aufgelöst, `vendor/` (inkl. Autoloader) committet.
- [x] PHPStan (Level max) + `phpstan.neon`.
- [x] Rector (`rector.php`) für automatisierte 8.4-Migration & laufende Upgrades.
- [x] **PSR-12 erzwingen** über `php-cs-fixer` (`@PSR12`) als Style-Tool (scoped auf `src/`, `qa-tests/unit`).
      Ersetzt das alte tab-basierte phpcs-Ruleset; das Legacy-`ruleset.xml` bleibt nur als Referenz liegen.
- [x] **CONTRIBUTING.md** auf 4 Spaces / PSR-12 / strict_types + Tooling aktualisiert.
- [x] Modernes Test-Setup: `phpunit.xml.dist` (PHPUnit 11, Attribute) + Smoke-Test `qa-tests/unit/`.
      *Die alten `qa-tests/tests` (prozeduraler Code) werden nicht migriert — neue Tests entstehen
      mit dem `src/`-Code in Phase 3/4.*
- [x] GitHub Actions CI: `composer validate`, php-cs-fixer, phpstan, phpunit (PHP 8.4).
- [x] `SECURITY.md`, `CHANGELOG.md` (Keep a Changelog), SemVer als Versionsschema.

**DoD:** ✅ erreicht — `composer install` grün, `composer check` (cs + stan + test) grün, CI-Workflow vorhanden, Smoke-Test grün.

## Phase 2 — Architektur-Grundgerüst

- [x] Single Front Controller (`public/index.php`), HttpFoundation Request/Response.
- [ ] Router, DI-Container, Konfiguration:
  - [x] Router (Symfony Routing) + erste Route, in den Kernel verdrahtet (Controller-Interface, 404-Handling).
  - [ ] DI-Container (Symfony DependencyInjection) + Controller-Resolver darüber.
  - [ ] Konfiguration via `.env` (statt `qa-config.php`).
- [ ] Doctrine DBAL Connection-Factory (ersetzt [qa-include/qa-db.php](qa-include/qa-db.php)).
- [ ] Twig-Integration mit striktem Auto-Escaping; Basis-Layout mit Landmark-Rollen (a11y).
- [ ] Fehler-/Exception-Handling, strukturiertes Logging (PSR-3 / Monolog).

**DoD:** „Hello, Q2A" über den neuen Front Controller, aus DB gelesen, durch Twig gerendert.

## Phase 3 — Datenbank-Schicht

- [ ] Schema von 1.8.8 erfassen ([qa-include/qa-db-install.php](qa-include/qa-db-install.php)) und in
      Doctrine-Migrations gießen.
- [ ] Repository-Klassen je Aggregat (User, Post, Vote, Tag, Category, Meta …) auf DBAL-QueryBuilder,
      durchgängig prepared statements, getypte Rückgaben (DTOs/`readonly`).
- [ ] Enums für Status/Typen statt magischer Strings/Ints.

**DoD:** Migrations laufen idempotent; Repositories durch Unit-/Integrationstests abgedeckt.

## Phase 4 — Domänen-Kern (Feature-Parität, iterativ)

Pro Domäne: Service + Repository + Tests + Twig-Views. Reihenfolge nach Abhängigkeit:

- [ ] Auth & User-Management (Registrierung, Login, Sessions, Rollen, Punkte/Reputation).
- [ ] Posts: Fragen / Antworten / Kommentare (erstellen, bearbeiten, schließen, Folgefragen).
- [ ] Voting, Best-Answer, Favoriten/Following.
- [ ] Kategorien (bis 4 Ebenen) & Tags.
- [ ] Suche (zunächst DB-basiert; Such-Abstraktion für spätere Engines).
- [ ] Moderation/Flagging/Spam (Captcha-Abstraktion, Rate-Limiting).
- [ ] Benachrichtigungen, E-Mail (PHPMailer via Composer, **eine** Version), Feeds/RSS, PMs/Wall.

**DoD:** Jede Domäne hat grüne Tests und funktioniert end-to-end im neuen Stack.

## Phase 5 — Präsentation, Themes & a11y

- [ ] Controller-Schicht vollständig; alle Seiten aus [qa-include/qa-page-*.php](qa-include/) abgedeckt.
- [ ] Neues Theme-System (Twig-basiert) als Ersatz für [qa-theme/](qa-theme/) / [qa-theme-base.php](qa-include/qa-theme-base.php).
- [ ] **Dritt-Theme-API** definieren & dokumentieren: Theme = Paket-Verzeichnis
      (`theme.json` + `templates/`-Overrides + `assets/` + optional `Theme.php`); Override per
      Twig-Namespace `@core/…` und `{% extends %}`/`{% block %}`; CSS Custom Properties / Design-Tokens
      als offizielle Styling-Schnittstelle; Hook-Punkte für Plugin-Injektion.
- [ ] **WCAG 2.2 AA**: semantisches HTML, Tastatur-Navigation, Fokus-Management, ARIA nur wo nötig,
      Kontraste, `prefers-reduced-motion`, Skip-Links, Formular-Labels/Fehler.
- [ ] Progressive Enhancement; AJAX-Endpunkte ([qa-include/qa-ajax-*.php](qa-include/)) als saubere JSON-APIs neu.
- [ ] Automatisierte a11y-Checks in CI (axe-core / Pa11y).

**DoD:** Kernflows tastatur- und screenreader-bedienbar; a11y-CI grün.

## Phase 6 — Security-Härtung

- [ ] Passwort-Hashing: `password_hash` mit Argon2id-Evaluierung, Bcrypt-Kosten 12,
      `password_needs_rehash()` beim Login. `vendor/password_compat`-Shim entfernen.
- [ ] CSRF-Schutz, sichere Sessions/Cookies (`SameSite`, `Secure`, `HttpOnly`).
- [ ] HTML-Sanitizing: dedizierter Sanitizer (htmLawed bzw. HTML Purifier) — **nicht** durch
      DOM-HTML5 ersetzen (Parsen ≠ Filtern). DOM-HTML5 nur fürs Parsen.
- [ ] Security-Header (CSP, HSTS, X-Content-Type-Options …).
- [ ] Security-Review/Pen-Test-Checkliste; alle DB-Zugriffe prepared (aus Phase 3 verifiziert).

**DoD:** Security-Review ohne offene High/Critical-Findings.

## Phase 7 — i18n-Ausbau

- [ ] Symfony Translation mit ICU MessageFormat (Pluralregeln, Platzhalter, Geschlecht).
- [ ] Bestehende Sprachdaten aus [qa-lang/](qa-lang/) migrieren.
- [ ] Neue/aktualisierte Übersetzungen (Priorität: de, en, fr, es, it, pt, nl, pl, ru …).
- [ ] RTL-Support (ar, he): CSS Logical Properties, `dir`-Handling — verzahnt mit a11y.
- [ ] Workflow für Übersetzungsbeiträge dokumentieren.

**DoD:** Mind. die Kernsprachen vollständig; RTL-Layout korrekt; i18n-Lint grün.

## Phase 8 — Plugin- & Theme-API (neu)

- [ ] Moderne Erweiterungs-API definieren (Events/Hooks, DI, klare Interfaces) als Ersatz für
      [qa-include/Q2A/Plugin/PluginManager.php](qa-include/Q2A/Plugin/PluginManager.php) und das alte Modul-System.
- [ ] Beiliegende Plugins/Themes ([qa-plugin/](qa-plugin/), [qa-theme/](qa-theme/)) auf die neue API migrieren
      bzw. neu erstellen.
- [ ] Vom Nutzer später gelieferte „alte" Plugins anpassen/neu bauen.

**DoD:** Mitgelieferte Addons laufen auf der neuen API; API dokumentiert.

## Phase 9 — Datenmigration, Doku & Release

- [ ] Upgrade-/Migrationspfad von bestehenden 1.8.8-Datenbanken.
- [ ] Legacy-`qa-*`-Bäume entfernen, sobald Parität erreicht ist.
- [ ] Dokumentation (Install, Upgrade, Plugin-Dev), README aktualisieren.
- [ ] `CHANGELOG.md` finalisieren, Release-Tagging (SemVer).

**DoD:** Frische Installation + Upgrade aus 1.8.8 funktionieren; Doku vollständig; Release getaggt.

---

### Querschnitt (gilt in jeder Phase)

- `declare(strict_types=1)` in jeder neuen Datei; strikte Typen, kein implizites Nullable.
- Jede neue Klasse mit Unit-Tests in `qa-tests/` (committet).
- phpcs (PSR-12) + PHPStan (max) müssen grün sein, bevor eine Aufgabe als erledigt gilt.
- Lizenz-Header & Attribution gemäß CLAUDE.md.
