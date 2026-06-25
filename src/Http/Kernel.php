<?php

/*
    Question2Answer by Gideon Greenspan and contributors
    https://github.com/q2a/question2answer

    Modernization (PHP 8.4+, 2026) by Ramon Kaes

    File: src/Http/Kernel.php
    Description: HTTP kernel — matches the request to a route and dispatches it

    License: GPL-2.0-or-later — see LICENSE
*/

declare(strict_types=1);

namespace Q2A\Http;

use Psr\Container\ContainerInterface;
use Q2A\Http\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * The HTTP kernel is the single seam between the front controller and the
 * application: it matches the incoming request against the route collection and
 * dispatches it to the controller resolved from the DI container. The rendering
 * layer is wired in by the following Phase 2 steps.
 */
final class Kernel
{
    public function __construct(
        private readonly RouteCollection $routes,
        private readonly ContainerInterface $container,
    ) {
    }

    public function handle(Request $request): Response
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters = $matcher->matchRequest($request);
        } catch (ResourceNotFoundException) {
            return new Response(
                "Not Found\n",
                Response::HTTP_NOT_FOUND,
                ['Content-Type' => 'text/plain; charset=utf-8'],
            );
        }

        $controllerId = $parameters['_controller'] ?? null;
        if (!is_string($controllerId) || !$this->container->has($controllerId)) {
            throw new \RuntimeException('The matched route does not reference a known controller service.');
        }

        $controller = $this->container->get($controllerId);
        if (!$controller instanceof Controller) {
            throw new \RuntimeException(sprintf(
                'Controller service "%s" must implement %s.',
                $controllerId,
                Controller::class,
            ));
        }

        return $controller($request);
    }
}
