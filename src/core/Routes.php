<?php

namespace App\core;

use App\Controllers\AuthController;
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
            $pattern = preg_replace('/:\w+/', '(\w+)', $route['path']); // Remplace :id par une capture regex
            $pattern = '@^' . $pattern . '$@'; // Ajoute des délimiteurs pour Regex

            if (preg_match($pattern, $uri, $matches) && $route['method'] === $method) {
                array_shift($matches); // Enlève le premier élément (l'URL complète capturée)

                $controllerName = "App\\Controllers\\" . $route['controller'];
                $controller = new $controllerName();
                $action = $route['action'];

                if (method_exists($controller, $action)) {
                    $controller->$action(...$matches); // Passe les paramètres capturés à l'action
                    return;
                }
            }
        }

        (new ErrorController())->error404();
    }
}
