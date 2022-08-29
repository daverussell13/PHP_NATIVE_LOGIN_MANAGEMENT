<?php

namespace Daver\MVC\Service;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Repository\{
  UserRepository,
  SessionRepository
};
use Daver\MVC\Config\Database;
use Daver\MVC\Domain\User;
use Daver\MVC\Domain\Session;

function setcookie(string $name, string $value, int $sec, string $path)
{
  echo "$name : $value";
}

class SessionServiceTest extends TestCase
{
  private SessionService $sessionService;
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  private function createDummyUser(): void
  {
    $user = new User();
    $user->setId("test")
         ->setName("test")
         ->setPassword(password_hash("test", PASSWORD_BCRYPT));

    $this->userRepository->save($user);
  }

  protected function setUp(): void
  {
    $connection = Database::getConnection();

    $this->sessionRepository = new SessionRepository($connection);
    $this->userRepository = new UserRepository($connection);

    $this->createDummyUser();
    $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
  }

  protected function tearDown(): void
  {
    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();
  }

  public function testCreate(): void
  {
    $session_created = $this->sessionService->create("test");

    self::expectOutputRegex("[X-SESS-ID : {$session_created->getId()}]");

    $session = $this->sessionRepository->findById($session_created->getId());
    $user = $this->userRepository->findById($session->getUserId());

    self::assertEquals($user->getId(), "test");
    self::assertEquals($user->getName(), "test");
    self::assertTrue(password_verify("test", $user->getPassword()));
  }

  public function testDestroy(): void
  {
    $session = new Session();
    $session->setId(uniqid(more_entropy: true))
            ->setUserId("test");

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

    $this->sessionService->destroy();
    self::expectOutputRegex("[X-SESS-ID : ]");

    $session_result = $this->sessionRepository->findById($session->getId());
    self::assertNull($session_result);
  }

  public function testCurrent(): void
  {
    $session = new Session();
    $session->setId(uniqid(more_entropy: true))
            ->setUserId("test");

    $this->sessionRepository->save($session);

    $_COOKIE[SessionService::$COOKIE_NAME] = $session->getId();

    $user = $this->sessionService->current();
    self::assertEquals("test", $user->getId());
    self::assertEquals("test", $user->getName());
    self::assertTrue(password_verify("test", $user->getPassword()));
  }

  public function testCurrentNotFound(): void
  {
    $user = $this->sessionService->current();
    self::assertNull($user);
  }
}