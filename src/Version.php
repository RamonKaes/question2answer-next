<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Version.php
    Description: Central application version constant

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A;

/**
 * Holds the current application version. Single source of truth for the
 * modernized code base (replaces the legacy QA_VERSION define over time).
 */
final class Version
{
    public const string CURRENT = '2.0.0-dev';
}
