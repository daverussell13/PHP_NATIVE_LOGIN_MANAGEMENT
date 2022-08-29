<?php

namespace Daver\MVC\Service;

use PHPUnit\Framework\TestCase;
use Daver\MVC\Config\Database;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Models\UserRegisterRequest;
use Daver\MVC\Exception\ValidationException;
use Daver\MVC\Models\UserLoginRequest;

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

  public function testLoginFailed(): void
  {
    $request = new UserLoginRequest();

    self::expectException(ValidationException::class);
    self::expectExceptionMessage("Id, Password Cannot Null");

    $this->service->login($request);

    $request->id = "";
    $request->password = "";

    self::expectException(ValidationException::class);
    self::expectExceptionMessage("Id, Password Cannot Empty");

    $this->service->login($request);
  }

  public function testLoginWrongPassword(): void
  {
    $registerRequest = new UserRegisterRequest();
    $registerRequest->id = "test";
    $registerRequest->name = "test";
    $registerRequest->password = "test";

    $this->service->register($registerRequest);

    $loginRequest = new UserLoginRequest();
    $loginRequest->id = "test";
    $loginRequest->password = "wrong password";

    self::expectException(ValidationException::class);
    self::expectExceptionMessage("Id or Password is wrong");
    $this->service->login($loginRequest);
  }

  public function testLoginSuccess(): void
  {
    $registerRequest = new UserRegisterRequest();
    $registerRequest->id = "test";
    $registerRequest->name = "test";
    $registerRequest->password = "test";

    $this->service->register($registerRequest);

    $loginRequest = new UserLoginRequest();
    $loginRequest->id = "test";
    $loginRequest->password = "test";

    $response = $this->service->login($loginRequest);

    self::assertEquals($registerRequest->id, $response->user->getId());
    self::assertEquals($registerRequest->name, $response->user->getName());
    self::assertTrue(password_verify($registerRequest->password, $response->user->getPassword()));
  }
}