<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: config/container.php
    Description: Builds and compiles the dependency-injection container

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

use Q2A\Config\AppConfig;
use Q2A\Http\Controller\HomeController;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$container = new ContainerBuilder();

// Typed application configuration, built from the environment (symfony/dotenv).
$container->register(AppConfig::class, AppConfig::class)
    ->setFactory([AppConfig::class, 'fromEnv'])
    ->setPublic(true);

// Controllers are resolved from the container by the kernel, so they must be
// registered as public, autowired services. Further controllers are added here
// (directory-based autodiscovery is a later refinement).
$container->register(HomeController::class, HomeController::class)
    ->setAutowired(true)
    ->setPublic(true);

$container->compile();

return $container;
