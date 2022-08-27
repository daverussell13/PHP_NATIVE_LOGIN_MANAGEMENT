<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Daver\MVC\App\Router;
use Daver\MVC\Controllers\{
  HomeController,
  UserController
};

Router::add("GET", "/", HomeController::class, "index", []);
Router::add("GET", "/users/register", UserController::class, "register", []);

Router::run();
