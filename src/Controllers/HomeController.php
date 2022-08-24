<?php

namespace Daver\MVC\Controllers;

use Daver\MVC\App\View;

class HomeController
{
  public function index(): void
  {
    View::render("Home/index", [
      "title" => "PHP Login Management"
    ]);
  }
}