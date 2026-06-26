<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: qa-tests/unit/Http/Controller/HomeControllerTest.php
    Description: Tests for the home page controller

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Tests\Http\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Q2A\Config\AppConfig;
use Q2A\Http\Controller\HomeController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(HomeController::class)]
final class HomeControllerTest extends TestCase
{
    public function testReturnsRunningResponseIncludingEnvironment(): void
    {
        $controller = new HomeController(new AppConfig('test', false, ''));

        $response = $controller(Request::create('/'));

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertStringContainsString('is running', (string) $response->getContent());
        self::assertStringContainsString('env: test', (string) $response->getContent());
    }
}
