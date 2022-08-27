<?php

namespace Daver\MVC\Service;

use Daver\MVC\Models\{
  UserRegisterRequest,
  UserRegisterResponse,
};
use Daver\MVC\Repository\UserRepository;
use Daver\MVC\Exception\ValidationException;
use Daver\MVC\Domain\User;
use Daver\MVC\Config\Database;

class UserService
{
  private UserRepository $userRepository;

  public function __construct(UserRepository $repository)
  {
    $this->userRepository = $repository;
  }

  public function register(UserRegisterRequest $request): UserRegisterResponse
  {
    $this->validateRegisterRequest($request);
    try {
      Database::beginTransaction();

      $getUser = $this->userRepository->findById($request->id);
      if ($getUser) throw new ValidationException("User Id already exists");

      $user = new User();
      $user->setId($request->id)
           ->setName($request->name)
           ->setPassword(password_hash($request->password, PASSWORD_BCRYPT));

      $this->userRepository->save($user);

      $response = new UserRegisterResponse();
      $response->user = $user;

      Database::commitTransaction();
      return $response;
    }
    catch (\Exception $exception) {
      Database::rollbackTransaction();
      throw $exception;
    }
  }

  private function validateRegisterRequest(UserRegisterRequest $request): void
  {
    if ($request->id == null || $request->name == null || $request->password == null)
      throw new ValidationException("Id, Name, Password Cannot Null");

    if (trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "")
      throw new ValidationException("Id, Name, Password Cannot Empty");

    if (strlen($request->name) > User::MAX_NAME_LENGTH)
      throw new ValidationException("Name Cannot Exceed More Than " . User::MAX_NAME_LENGTH . " Characters");

    if (strlen($request->password) > User::MAX_PASS_LENGTH)
      throw new ValidationException("Password Cannot Exceed More Than " . User::MAX_PASS_LENGTH . " Characters");
  }
}