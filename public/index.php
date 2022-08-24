<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Daver\MVC\App\Router;
use Daver\MVC\Controllers\HomeController;

Router::add("GET", "/", HomeController::class, "index", []);

Router::run();
