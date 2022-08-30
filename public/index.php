<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Daver\MVC\App\Router;
use Daver\MVC\Config\Database;

use Daver\MVC\Controllers\{
  HomeController,
  UserController
};

use Daver\MVC\Middleware\{
  MustLoginMiddleware,
  MustNotLoginMiddleware
};

Database::getConnection("production");

Router::add("GET", "/", HomeController::class, "index", []);
Router::add("GET", "/users/register", UserController::class, "register", [MustNotLoginMiddleware::class]);
Router::add("POST", "/users/register", UserController::class, "registerPost", [MustNotLoginMiddleware::class]);
Router::add("GET", "/users/login", UserController::class, "login", [MustNotLoginMiddleware::class]);
Router::add("POST", "/users/login", UserController::class, "loginPost", [MustNotLoginMiddleware::class]);
Router::add("GET", "/users/logout", UserController::class, "logout", [MustLoginMiddleware::class]);

// Debug
Router::add("GET", "/debug/clear", UserController::class, "debugClear", []);

Router::run();
