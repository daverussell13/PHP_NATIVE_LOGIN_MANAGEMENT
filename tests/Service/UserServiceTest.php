<?php

namespace Daver\MVC\Service;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Config\Database;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Models\UserRegisterRequest;
use Daver\MVC\Exception\ValidationException;
use PHPUnit\Util\Xml\ValidationResult;

class UserServiceTest extends TestCase
{
  private UserService $service;

  protected function setUp(): void
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $this->service = new UserService($userRepository);
  }

  protected function tearDown(): void
  {
    $connection = Database::getConnection();
    $userRepository = new UserRepository($connection);
    $userRepository->deleteAll();
  }

  public function testRegisterSuccess(): void
  {
    $request = new UserRegisterRequest();
    $request->id = "test";
    $request->name = "test";
    $request->password = "01234567890123456789";

    $response = $this->service->register($request);

    self::assertEquals($request->id, $response->user->getId());
    self::assertEquals($request->name, $response->user->getName());
    self::assertNotEquals($request->password, $response->user->getPassword());
    self::assertTrue(password_verify($request->password, $response->user->getPassword()));
  }

  public function testRegisterFailed(): void
  {
    $request = new UserRegisterRequest();

    $request->name = "test";
    $request->password = "01234567890123456789";
    self::expectException(ValidationException::class);
    $this->service->register($request);

    $request->id = "test";
    $request->name = "";
    self::expectException(ValidationException::class);
    $this->service->register($request);

    $request->name = "012345678901234567890";
    self::expectException(ValidationException::class);
    $this->service->register($request);

    $request->password = "012345678901234567890";
    self::expectException(ValidationException::class);
    $this->service->register($request);
  }

  public function testRegisterDuplicate(): void
  {
    $requestBefore = new UserRegisterRequest();
    $requestBefore->id = "test";
    $requestBefore->name = "test";
    $requestBefore->password = "test";
    $this->service->register($requestBefore);

    self::expectException(ValidationException::class);

    $requestNow = new UserRegisterRequest();
    $requestNow->id = "test";
    $requestNow->name = "test";
    $requestNow->password = "test";
    $this->service->register($requestNow);
  }
}