<?php

namespace Daver\MVC\Repository;

use Daver\MVC\Domain\Session;
use Daver\MVC\Config\Database;

class SessionRepository
{
  private \PDO $connection;

  public function __construct()
  {
    $this->connection = Database::getConnection();
  }

  public function save(Session $session): Session
  {
    $statement = $this->connection->prepare("INSERT INTO sessions (id, user_id) VALUES (?, ?)");
    $statement->execute([$session->getId(), $session->getUserId()]);
    return $session;
  }

  public function findById(string $id): ?Session
  {
    $statement = $this->connection->prepare("SELECT id, user_id FROM sessions WHERE id = ?");
    $statement->execute([$id]);
    try {
      if (($result = $statement->fetch()))
      {
        $session = new Session();
        $session->setId($result["id"])
                ->setUserId($result["user_id"]);
        return $session;
      }
      else return null;
    } finally {
      $statement->closeCursor();
    }
  }

  public function deleteById(string $id): void
  {
    $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
    $statement->execute([$id]);
  }

  public function deleteAll(): void
  {
    $this->connection->exec("DELETE FROM sessions");
  }

}