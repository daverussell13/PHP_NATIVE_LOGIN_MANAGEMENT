<?php

namespace Daver\MVC\App;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
  public function testRender()
  {
    View::render("Home/index", [
      "title" => "PHP Login Management"
    ]);

    self::expectOutputRegex("[PHP Login Management]");
    self::expectOutputRegex("[<html>]");
    self::expectOutputRegex("[<body>]");
    self::expectOutputRegex("[</body>]");
    self::expectOutputRegex("[</html]");
    self::expectOutputRegex("[Login Management]");
    self::expectOutputRegex("[Login]");
    self::expectOutputRegex("[Register]");
  }
}
