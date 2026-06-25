<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Http/Kernel.php
    Description: Minimal HTTP kernel turning a Request into a Response

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Http;

use Q2A\Version;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The HTTP kernel is the single entry seam between the front controller and the
 * application. For now it returns a placeholder response; routing, the DI
 * container and the rendering layer are wired in by the following Phase 2 steps.
 */
final class Kernel
{
    public function handle(Request $request): Response
    {
        return new Response(
            'Question2Answer ' . Version::CURRENT . " is running.\n",
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; charset=utf-8'],
        );
    }
}
