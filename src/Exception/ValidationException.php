<?php

namespace Daver\MVC\Exception;

class ValidationException extends \Exception
{
  public function errorMessage(): string
  {
    $errMsg = "Error : " . $this->getMessage();
    return $errMsg;
  }
}