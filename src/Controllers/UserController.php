<?php

namespace Daver\MVC\Controllers;

use Daver\MVC\App\View;
use Daver\MVC\Models\{
  UserRegisterRequest,
  UserLoginRequest
};
use Daver\MVC\Service\UserService;
use Daver\MVC\Exception\ValidationException;
use Daver\MVC\Config\Database;
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Service\SessionService;
use Daver\MVC\Repository\SessionRepository;

class UserController
{
  private UserService $userService;
  private SessionService $sessionService;

  public function __construct()
  {
    $connection = Database::getConnection();

    $userRepository = new UserRepository($connection);
    $this->userService = new UserService($userRepository);

    $sessionRepository = new SessionRepository($connection);
    $this->sessionService = new SessionService($sessionRepository, $userRepository);
  }

  public function register(): void
  {
    View::render("User/register", [
      "title" => "Register"
    ]);
  }

  public function registerPost(): void
  {
    $request = new UserRegisterRequest();
    $request->id = $_POST["id"];
    $request->name = $_POST["name"];
    $request->password = $_POST["password"];

    try {
      $this->userService->register($request);
      View::redirect("/users/login");
    }
    catch (ValidationException $exception) {
      View::render("User/register",[
        "title" => "Register",
        "error" => $exception->errorMessage()
      ]);
    }
  }

  public function login(): void
  {
    View::render("User/login",[
      "title" => "Login"
    ]);
  }

  public function loginPost(): void
  {
    $request = new UserLoginRequest();
    $request->id = $_POST["id"];
    $request->password = $_POST["password"];

    try {
      $response = $this->userService->login($request);
      $this->sessionService->create($response->user->getId());
      View::redirect("/");
    }
    catch (ValidationException $exception)
    {
      View::render("User/login", [
        "title" => "Login",
        "error" => $exception->errorMessage()
      ]);
    }
  }

  public function logout(): void
  {
    $this->sessionService->destroy();
    View::redirect("/");
  }

  public function debugClear(): void
  {
    $connection = Database::getConnection();
    $repository = new UserRepository($connection);
    $repository->deleteAll();
    View::redirect("/");
  }
}