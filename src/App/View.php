<?php

namespace Daver\MVC\App;

class View
{
  public static function render(string $view, mixed $model): void
  {
    require __DIR__ . "/../Views/header.php";
    require __DIR__ . "/../Views/" . $view . ".php";
    require __DIR__ . "/../Views/footer.php";
  }

  public static function redirect(string $url): void
  {
    header("Location: " . $url);
  }
}