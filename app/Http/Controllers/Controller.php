<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Returns the endpoints starting with the current endpoint
     * For instance, in route github/, it will return all endpoints that starts with github/ (github/*) in an array containing in the name as key, and the Route object as value
     *
     * @return array<string, Route>
     */
    private function getUriSubEndpoints(Router $router, string $uri): array
    {
        $routes = $router->getRoutes();

        // Handle case for home endpoint
        // Only retrieve index endpoints
        if ($uri === '/') {
            $routes = array_filter(
                $routes->getRoutes(),
                // Return all routes that are only indexes (only one depth), they are sort of subsets of base "/" route but were not traited as if in default case
                // Ex: /github, /spotify
                // Filtered : /github/commits, /spotify/...
                fn (Route $route) => count(explode('/', $route->uri)) === 1
            );

            $names = array_map(fn (Route $endpoint) => $endpoint->uri, [...$routes]);
        } else {
            $routes = array_filter(
                $routes->getRoutes(),
                // Return all routes that are subsets of current endpoint
                fn (Route $route) => explode('/', $route->uri)[0] === $uri
            );

            $names = array_map(fn (Route $endpoint) => last(explode('/', $endpoint->uri)), [...$routes]);
        }

        return array_combine($names, $routes);
    }

    /**
     * Build an array containing all endpoints starting with the current uri, excluding current route.
     * Instead of overriding an index() method, I prefer reimplement index() in each controller. It allow implementation of index() methods that don't list uri endpoints (like AboutMeController's index() method)
     *
     * @return array<string, string>
     */
    public function buildIndexContent(): array
    {
        $router = app(Router::class);
        $uri = $router->getCurrentRoute()?->uri;

        if (!$uri) {
            return [];
        }

        $endpoints = $this->getUriSubEndpoints($router, $uri);
        // Exclude current uri from the endpoints
        $endpoints = array_filter($endpoints, fn (Route $endpoint) => $endpoint->uri !== $uri);

        $response = [];

        foreach ($endpoints as $endpoint) {
            /** @var string $name getName() returns a string and the key of endpoint array is always a string */
            $name = $endpoint->getName() ?? array_search($endpoint, $endpoints, true);
            $response[$name] = url($endpoint->uri);
        }

        return $response;
    }

    /**
     * @see buildIndexContent()
     */
    public function buildIndexResponse(): JsonApiResponse
    {
        return new JsonApiResponse($this->buildIndexContent());
    }
}
