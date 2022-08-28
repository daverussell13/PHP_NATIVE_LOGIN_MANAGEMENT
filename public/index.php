<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Daver\MVC\App\Router;
use Daver\MVC\Config\Database;
use Daver\MVC\Controllers\{
  HomeController,
  UserController
};

Database::getConnection("production");

Router::add("GET", "/", HomeController::class, "index", []);
Router::add("GET", "/users/register", UserController::class, "register", []);
Router::add("POST", "/users/register", UserController::class, "registerPost", []);
Router::add("GET", "/users/login", UserController::class, "login", []);

// Debug
Router::add("GET", "/debug/clear", UserController::class, "debugClear", []);

Router::run();
