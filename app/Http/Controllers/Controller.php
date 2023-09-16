<?php

namespace App\Http\Controllers;

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
     * For instance, in route github/, it will return all endpoints that starts with github/ (github/*)
     *
     * @return Route[]
     */
    private function getUriEndpoints(Router $router, string $uri): array
    {
        $routes = $router->getRoutes();

        return array_filter(
            $routes->getRoutes(),
            fn ($route) => explode('/', $route->uri)[0] === $uri
        );
    }

    /**
     * Build an array containing all endpoints starting with the current uri, excluding current route.
     * Instead of overriding an index() method, I prefer reimplement index() in each controller. It allow implementation of index() methods that don't list uri endpoints (like AboutMeController's index() method)
     *
     * @return array<string, string>
     */
    public function buildIndexResponse(): array
    {
        $router = app(Router::class);
        $uri = $router->getCurrentRoute()?->uri;

        if (!$uri) {
            return [];
        }

        $endpoints = $this->getUriEndpoints($router, $uri);
        // Exclude current uri from the endpoints
        $endpoints = array_filter($endpoints, fn (Route $endpoint) => $endpoint->uri !== $uri);

        $response = [];

        foreach ($endpoints as $endpoint) {
            $name = $endpoint->getName() ?? explode('/', $endpoint->uri)[1];
            $response[$name] = url($endpoint->uri);
        }

        return $response;
    }
}
