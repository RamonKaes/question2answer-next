<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: qa-tests/unit/Http/KernelTest.php
    Description: Tests for the minimal HTTP kernel

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Tests\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Q2A\Http\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    public function testHandleReturnsSuccessfulResponse(): void
    {
        $response = new Kernel()->handle(Request::create('/'));

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertStringContainsString('Question2Answer', (string) $response->getContent());
    }
}
