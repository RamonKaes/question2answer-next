<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Http/Controller/Controller.php
    Description: Contract every HTTP controller fulfils

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A controller turns a Request into a Response. Routes reference an
 * implementing class via their `_controller` default; the kernel resolves and
 * invokes it.
 */
interface Controller
{
    public function __invoke(Request $request): Response;
}
