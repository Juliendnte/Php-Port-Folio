<?php

namespace App\core;

use App\Controllers\ErrorController;

class Routes
{
    private array $routes = [];

    public function addRoute($method, $path, $controller, $action): void
    {
        $this->routes[] = [
            "method" => $method,
            "path" => $path,
            "controller" => $controller,
            "action" => $action
        ];
    }

    public function dispatch($uri, $method): void
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/:\w+/', '(\w+)', $route['path']);
            $pattern = '@^' . $pattern . '$@';

            if (preg_match($pattern, $uri, $matches) && $route['method'] === $method) {
                array_shift($matches);

                $controllerName = "App\\Controllers\\" . $route['controller'];
                $controller = new $controllerName();
                $action = $route['action'];

                if (method_exists($controller, $action)) {
                    $controller->$action(...$matches);
                    return;
                }
            }
        }

        (new ErrorController())->error404();
    }
}
