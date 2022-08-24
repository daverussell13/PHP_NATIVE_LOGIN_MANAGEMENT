<?php

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
}