<?php

namespace App\core;

use App\Controllers\ErrorController;

class routes
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
            if ($route['path'] === $uri && $route['method'] === $method) {
                $controllerName = "App\\Controllers\\" . $route['controller'];
                $controller = new $controllerName();
                $action = $route['action'];
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        (new ErrorController())->error404();
    }
}
