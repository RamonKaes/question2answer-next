<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: public/index.php
    Description: Front controller — the single entry point for all web requests

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

use Q2A\Http\Kernel;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$request = Request::createFromGlobals();
$response = new Kernel()->handle($request);
$response->send();
