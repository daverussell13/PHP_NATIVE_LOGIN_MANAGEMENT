<?php

namespace Daver\MVC\App;

class View
{
  public static function render(string $view, mixed $model): void
  {
    require __DIR__ . "/../Views" . $view . ".php";
  }
}