<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Config/AppConfig.php
    Description: Typed application configuration sourced from the environment

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Config;

/**
 * Immutable, typed view of the application configuration. Replaces the loose
 * defines of the legacy qa-config.php with a single injectable value object.
 */
final readonly class AppConfig
{
    public function __construct(
        public string $environment,
        public bool $debug,
        public string $databaseUrl,
    ) {
    }

    /**
     * Build the configuration from environment variables (populated by
     * symfony/dotenv). An explicit map can be passed for testing.
     *
     * @param array<string, mixed>|null $env
     */
    public static function fromEnv(?array $env = null): self
    {
        $env ??= $_ENV + $_SERVER;

        return new self(
            environment: self::string($env, 'APP_ENV', 'prod'),
            debug: self::bool($env, 'APP_DEBUG', false),
            databaseUrl: self::string($env, 'DATABASE_URL', ''),
        );
    }

    /**
     * @param array<array-key, mixed> $env
     */
    private static function string(array $env, string $key, string $default): string
    {
        $value = $env[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : $default;
    }

    /**
     * @param array<array-key, mixed> $env
     */
    private static function bool(array $env, string $key, bool $default): bool
    {
        $value = $env[$key] ?? null;

        return is_string($value) ? filter_var($value, FILTER_VALIDATE_BOOL) : $default;
    }
}
