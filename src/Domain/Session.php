<?php

namespace Daver\MVC\Domain;

class Session
{
  private string $id;
  private string $userId;

  public function getId(): string
  {
    return $this->id;
  }

  public function setId($id): Session
  {
    $this->id = $id;
    return $this;
  }

  public function getUserId(): string
  {
    return $this->userId;
  }

  public function setUserId($userId): Session
  {
    $this->userId = $userId;
    return $this;
  }
}