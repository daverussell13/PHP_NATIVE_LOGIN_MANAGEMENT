<?php

namespace Daver\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Domain\Session;
use Daver\MVC\Config\Database;
use Daver\MVC\Domain\User;

class SessionRepositoryTest extends TestCase
{
  private SessionRepository $repository;

  protected function setUp(): void
  {
    $user = new User();
    $user->setId("test")
         ->setName("test")
         ->setPassword(password_hash("test", PASSWORD_BCRYPT));

    $this->repository = new SessionRepository(Database::getConnection());
    $this->repository->deleteAll();

    $userRepository = new UserRepository(Database::getConnection());
    $userRepository->deleteAll();

    $userRepository->save($user);
  }

  public function testSave(): void
  {
    $session = new Session();
    $session->setId(uniqid())
            ->setUserId("test");

    $this->repository->save($session);

    $result = $this->repository->findById($session->getId());

    self::assertEquals($result->getId(), $session->getId());
    self::assertEquals($result->getUserId(), $session->getUserId());
  }

  public function testDeleteById(): void
  {
    $session = new Session();
    $session->setId(uniqid())
            ->setUserId("test");

    $this->repository->save($session);
    $result = $this->repository->findById($session->getId());

    self::assertEquals($result->getId(), $session->getId());
    self::assertEquals($result->getUserId(), $session->getUserId());

    $this->repository->deleteById($session->getId());

    $result = $this->repository->findById($session->getId());
    self::assertNull($result);
  }

  public function testFindByIdNotFound(): void
  {
    $result = $this->repository->findById("notfound");
    self::assertNull($result);
  }
}