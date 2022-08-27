<?php

namespace Daver\MVC\Controllers;
use Daver\MVC\App\View;

class UserController
{
  public function register(): void
  {
    View::render("User/register", [
      "title" => "Register"
    ]);
  }
}