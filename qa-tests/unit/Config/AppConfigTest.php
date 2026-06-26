<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: qa-tests/unit/Config/AppConfigTest.php
    Description: Tests for the typed application configuration

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Tests\Config;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Q2A\Config\AppConfig;

#[CoversClass(AppConfig::class)]
final class AppConfigTest extends TestCase
{
    public function testReadsValuesFromEnvironmentMap(): void
    {
        $config = AppConfig::fromEnv([
            'APP_ENV' => 'test',
            'APP_DEBUG' => '1',
            'DATABASE_URL' => 'mysql://u:p@db/q2a',
        ]);

        self::assertSame('test', $config->environment);
        self::assertTrue($config->debug);
        self::assertSame('mysql://u:p@db/q2a', $config->databaseUrl);
    }

    public function testFallsBackToDefaultsWhenUnset(): void
    {
        $config = AppConfig::fromEnv([]);

        self::assertSame('prod', $config->environment);
        self::assertFalse($config->debug);
        self::assertSame('', $config->databaseUrl);
    }
}
