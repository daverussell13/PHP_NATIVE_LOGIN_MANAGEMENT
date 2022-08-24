<?php

namespace Daver\MVC\Repository;

use Daver\MVC\Domain\User;


class UserRepository
{
  private \PDO $connection;

  public function __construct(\PDO $connection)
  {
    $this->connection = $connection;
  }

  public function save(User $user): User
  {
    $statement = $this->connection->prepare(
      "INSERT INTO users(id, name, password) VALUES (?, ?, ?)"
    );

    $statement->execute([
      $user->getId(),
      $user->getName(),
      $user->getPassword
    ]);

    return $user;
  }
}