<?php

namespace Daver\MVC\Middleware;

interface Middleware
{
  public function before(): void;
}