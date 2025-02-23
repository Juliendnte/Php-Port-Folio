<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__) . '/.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\core\Routes;

$router = new Routes();

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
$router->addRoute("GET", "/projects/add", "ProjectController", "addProject");
$router->addRoute("POST", "/projects/add", "ProjectController", "create");
$router->addRoute("GET", "/projects/delete/:id", "ProjectController", "deleteProject");
$router->addRoute("GET", "/projects/update/:id", "ProjectController", "updateProject");
$router->addRoute("POST", "/projects/update/:id", "ProjectController", "update");

$router->addRoute("POST", "/skill/add", "SkillController", "addSkill");
$router->addRoute("GET", "/skill/delete/:id", "SkillController", "deleteSkill");
$router->addRoute("GET", "/profile/skill/delete/:id", "SkillController", "deleteUserSkill");
$router->addRoute("POST", "/profile/skill/update/:id", "SkillController", "updateUserSkill");
$router->addRoute("POST", "/profile/addSkill", "SkillController", "addUserSkill");

$router->addRoute("GET", "/error/500", "ErrorController", "error500");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);