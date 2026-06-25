<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: qa-tests/unit/Http/KernelTest.php
    Description: Tests for the routing HTTP kernel

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Tests\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Q2A\Http\Controller\HomeController;
use Q2A\Http\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    public function testHandleDispatchesMatchedRouteToControllerFromContainer(): void
    {
        $routes = new RouteCollection();
        $routes->add('home', new Route('/', ['_controller' => HomeController::class]));

        $container = new ContainerBuilder();
        $container->register(HomeController::class, HomeController::class)->setPublic(true);
        $container->compile();

        $response = new Kernel($routes, $container)->handle(Request::create('/'));

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertStringContainsString('Question2Answer', (string) $response->getContent());
    }

    public function testHandleReturnsNotFoundForUnknownRoute(): void
    {
        $container = new ContainerBuilder();
        $container->compile();

        $response = new Kernel(new RouteCollection(), $container)->handle(Request::create('/missing'));

        self::assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
