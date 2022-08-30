<?php

namespace Daver\MVC\Controllers;

use Daver\MVC\App\View;
use Daver\MVC\Service\SessionService;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Config\Database;
use Daver\MVC\Repository\SessionRepository;

class HomeController
{
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  public function index(): void
  {
    $user = $this->sessionService->current();
    if ($user)
    {
      View::render("Home/dashboard", [
        "title" => "Dashboard",
        "name" => $user->getName()
      ]);
    }
    else
    {
      View::render("Home/index", [
        "title" => "PHP Login Management"
      ]);
    }
  }
}