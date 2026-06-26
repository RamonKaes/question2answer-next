<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Http/Controller/HomeController.php
    Description: Placeholder home page controller

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Http\Controller;

use Q2A\Config\AppConfig;
use Q2A\Version;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class HomeController implements Controller
{
    public function __construct(private AppConfig $config)
    {
    }

    public function __invoke(Request $request): Response
    {
        return new Response(
            sprintf(
                "Question2Answer %s is running (env: %s).\n",
                Version::CURRENT,
                $this->config->environment,
            ),
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain; charset=utf-8'],
        );
    }
}
