<?php

namespace Daver\MVC\Controllers;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Domain\User;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Repository\SessionRepository;
use Daver\MVC\Config\Database;
use Daver\MVC\Domain\Session;
use Daver\MVC\Service\SessionService;

class HomeControllerTest extends TestCase
{
  private HomeController $controller;
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  protected function setUp(): void
  {
    $this->userRepository = new UserRepository(Database::getConnection());
    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->controller = new HomeController();
  }

  protected function tearDown(): void
  {
    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();
  }

  public function testGuest(): void
  {
    $this->controller->index();
    self::expectOutputRegex("[Register]");
    self::expectOutputRegex("[Login]");
    self::expectOutputRegex("[PHP Login Management]");
  }

  public function testUserLogin(): void
  {
    $user = new User();
    $user->setId("test")
         ->setName("test")
         ->setPassword(password_hash("test", PASSWORD_BCRYPT));

    $this->userRepository->save($user);

    $session = new Session();
    $session->setId(uniqid(more_entropy: true))
            ->setUserId("test");

    $this->sessionRepository->save($session);
    $_COOKIE[SessionService::$COOKIE_NAME] =  $session->getId();

    $this->controller->index();

    self::expectOutputRegex("[Hello {$user->getName()}]");
    self::expectOutputRegex("[Dashboard]");
    self::expectOutputRegex("[Profile]");
    self::expectOutputRegex("[Password]");
    self::expectOutputRegex("[Logout]");
  }
}
