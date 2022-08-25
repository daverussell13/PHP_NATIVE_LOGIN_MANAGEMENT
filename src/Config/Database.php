<?php

namespace Daver\MVC\Config;

class Database
{
  private static ?\PDO $pdo = null;

  public static function getConnection(string $env = "test"): \PDO
  {
    if (!self::$pdo)
    {
      require_once __DIR__ . "/../../config/db_config.php";
      $config = getDatabaseConfig();
      self::$pdo = new \PDO (
        dsn: $config["database"][$env]["dsn"],
        username: $config["database"][$env]["username"],
        password: $config["database"][$env]["password"]
      );
    }
    return self::$pdo;
  }

  public static function beginTransaction(): bool
  {
    return self::$pdo->beginTransaction();
  }

  public static function commitTransaction(): bool
  {
    return self::$pdo->commit();
  }

  public static function rollbackTransaction(): bool
  {
    return self::$pdo->rollBack();
  }
}