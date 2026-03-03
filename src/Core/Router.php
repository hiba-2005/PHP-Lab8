<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "Method Not Allowed";
            return;
        }

        foreach ($this->routes[$method] as $route => $handler) {

            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {

                array_shift($matches); // retire match complet
                call_user_func($handler, $request, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}