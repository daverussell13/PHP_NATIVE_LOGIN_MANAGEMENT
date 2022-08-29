<?php

namespace Daver\MVC\App
{
  function header(string $str)
  {
    echo $str;
  }
};

namespace Daver\MVC\Controllers
{
  use PHPUnit\Framework\TestCase;
  use Daver\MVC\Repository\UserRepository;
  use Daver\MVC\Config\Database;
  use Daver\MVC\Domain\User;

  class UserControllerTest extends TestCase
  {
    private UserRepository $userRepository;
    private UserController $controller;

    protected function setUp(): void
    {
      $this->userRepository = new UserRepository(Database::getConnection());
      $this->userRepository->deleteAll();
      $this->controller = new UserController();
    }

    public function testRegister()
    {
      $this->controller->register();
      self::expectOutputRegex("[Register]");
      self::expectOutputRegex("[Id]");
      self::expectOutputRegex("[Name]");
      self::expectOutputRegex("[Password]");
    }

    public function testRegisterPostSuccess()
    {
      $_POST["id"] = "test";
      $_POST["name"] = "test";
      $_POST["password"] = "test";

      $this->controller->registerPost();
      self::expectOutputRegex("[Location: /users/login]");
    }

    public function testRegisterPostFailed()
    {
      $_POST["id"] = "";
      $_POST["name"] = "";
      $_POST["password"] = "";

      $this->controller->registerPost();
      self::expectOutputRegex("[Id, Name, Password Cannot Empty]");

      $_POST["id"] = "test";
      $_POST["name"] = "012345678901234567890";
      $_POST["password"] = "test";

      $this->controller->registerPost();
      self::expectOutputRegex("[Name Cannot Exceed More Than " . User::MAX_NAME_LENGTH . " Characters]");

      $_POST["id"] = "test";
      $_POST["name"] = "test";
      $_POST["password"] = "012345678901234567890";

      $this->controller->registerPost();
      self::expectOutputRegex("[Password Cannot Exceed More Than " . User::MAX_PASS_LENGTH . " Characters]");
    }

    public function testRegisterDuplicate()
    {
      $userInDb = new User();
      $userInDb->setId("test")
              ->setName("test")
              ->setPassword("test");

      $this->userRepository->save($userInDb);

      $_POST["id"] = "test";
      $_POST["name"] = "test";
      $_POST["password"] = "test";

      $this->controller->registerPost();

      self::expectOutputRegex("[User Id already exists]");
    }

    public function testLogin(): void
    {
      $this->controller->login();
      self::expectOutputRegex("[Login]");
      self::expectOutputRegex("[Id]");
      self::expectOutputRegex("[Password]");
    }

    public function testLoginPostSuccess(): void
    {
      self::markTestSkipped("Blom beres, beresin dong");
      $user = new User();
      $user->setId("test")
           ->setName("test")
           ->setPassword(password_hash("test", PASSWORD_BCRYPT));

      $this->userRepository->save($user);

      $_POST["id"] = "test";
      $_POST["password"] = "test";

      $this->controller->loginPost();
      // TODO: Blom beres
    }

    public function testLoginPostValidationError(): void
    {
      $_POST["id"] = "";
      $_POST["password"] = "";

      $this->controller->loginPost();
      self::expectOutputRegex("[Error : Id, Password Cannot Empty]");

      $_POST["id"] = null;
      $_POST["password"] = null;

      $this->controller->loginPost();
      self::expectOutputRegex("[Error : Id, Password Cannot Null]");
    }

    public function testLoginNotFound(): void
    {
      $_POST["id"] = "test";
      $_POST["password"] = "test";

      $this->controller->loginPost();
      self::expectOutputRegex("[Error : Id or Password is wrong]");
    }

    public function testLoginWrongPassword(): void
    {
      $user = new User();
      $user->setId("test")
           ->setName("test")
           ->setPassword(password_hash("test", PASSWORD_BCRYPT));

      $this->userRepository->save($user);

      $_POST["id"] = "test";
      $_POST["password"] = "wrong password";

      $this->controller->loginPost();
      self::expectOutputRegex("[Error : Id or Password is wrong]");
    }
  }
};
