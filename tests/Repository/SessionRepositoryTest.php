<?php

namespace Daver\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Domain\Session;
use Daver\MVC\Config\Database;
use Daver\MVC\Domain\User;

class SessionRepositoryTest extends TestCase
{
  private SessionRepository $sessionRepository;
  private UserRepository $userRepository;

  protected function setUp(): void
  {
    $user = new User();
    $user->setId("test")
         ->setName("test")
         ->setPassword(password_hash("test", PASSWORD_BCRYPT));

    $this->sessionRepository = new SessionRepository(Database::getConnection());
    $this->userRepository = new UserRepository(Database::getConnection());

    $this->userRepository->save($user);
  }

  protected function tearDown(): void
  {
    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();
  }

  public function testSave(): void
  {
    $session = new Session();
    $session->setId(uniqid())
            ->setUserId("test");

    $this->sessionRepository->save($session);

    $result = $this->sessionRepository->findById($session->getId());

    self::assertEquals($result->getId(), $session->getId());
    self::assertEquals($result->getUserId(), $session->getUserId());
  }

  public function testDeleteById(): void
  {
    $session = new Session();
    $session->setId(uniqid())
            ->setUserId("test");

    $this->sessionRepository->save($session);
    $result = $this->sessionRepository->findById($session->getId());

    self::assertEquals($result->getId(), $session->getId());
    self::assertEquals($result->getUserId(), $session->getUserId());

    $this->sessionRepository->deleteById($session->getId());

    $result = $this->sessionRepository->findById($session->getId());
    self::assertNull($result);
  }

  public function testFindByIdNotFound(): void
  {
    $result = $this->sessionRepository->findById("notfound");
    self::assertNull($result);
  }
}