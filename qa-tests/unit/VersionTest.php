<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: qa-tests/unit/VersionTest.php
    Description: Smoke test that validates the modernized PSR-4 test pipeline

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Q2A\Version;

#[CoversClass(Version::class)]
final class VersionTest extends TestCase
{
    public function testCurrentVersionIsANonEmptyString(): void
    {
        self::assertNotSame('', Version::CURRENT);
    }
}
