<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__).'/.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\core\routes;

$router = new routes();

$router->addRoute("GET", "/", "HomeController", "index");
$router->addRoute("GET", "/profile", "UserController", "profile");
$router->addRoute("GET", "/profile/update", "UserController", "updateUser");
$router->addRoute("POST", "/profile/update", "UserController", "update");
$router->addRoute("GET", "/dashboard", "UserController", "dashboard");
$router->addRoute("GET", "/login", "AuthController", "login");
$router->addRoute("POST", "/login", "AuthController", "authenticate");
$router->addRoute("GET", "/register", "AuthController", "register");
$router->addRoute("POST", "/register", "AuthController", "record");
$router->addRoute("GET", "/logout", "AuthController", "logout");
$router->addRoute("GET", "/projects", "ProjectController", "listProjects");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);