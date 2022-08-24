<?php

function getDatabaseConfig(): array
{
  return [
    "database" => [
      "production" => [
        "dsn" => "mysql:host=localhost:3306;dbname=php_login_management",
        "username" => "php-login-management",
        "password" => "user"
      ],
      "test" => [
        "dsn" => "mysql:host=localhost:3306;dbname=php_login_management_test",
        "username" => "php-login-management-test",
        "password" => "test"
      ]
    ]
  ];
}