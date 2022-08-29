<?php

namespace Daver\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Config\Database;
use Daver\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{
  private UserRepository $repository;

  protected function setUp(): void
  {
    $this->repository = new UserRepository(Database::getConnection());
  }

  protected function tearDown(): void
  {
    $this->repository->deleteAll();
  }

  public function testSaveSuccess(): void
  {
    $user = new User();

    $user->setId("1")
         ->setName("test")
         ->setPassword("test");

    $result = $this->repository->save($user);
    self::assertEquals($user, $result);

    $getUser = $this->repository->findById($user->getId());
    self::assertEquals($user, $getUser);
  }

  public function testFindByIdNotFound(): void
  {
    $getUser = $this->repository->findById("1");
    self::assertNull($getUser);
  }
}