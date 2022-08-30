<?php

namespace Daver\MVC\Middleware;

use Daver\MVC\Service\SessionService;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Repository\SessionRepository;
use Daver\MVC\Config\Database;
use Daver\MVC\App\View;

class MustLoginMiddleware implements Middleware
{
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  public function before(): void
  {
    $user = $this->sessionService->current();
    if (!$user) View::redirect("/users/login");
  }
}
