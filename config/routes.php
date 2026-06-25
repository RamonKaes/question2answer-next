<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: config/routes.php
    Description: Application route collection

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

use Q2A\Http\Controller\HomeController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('home', new Route('/', ['_controller' => HomeController::class]));

return $routes;
