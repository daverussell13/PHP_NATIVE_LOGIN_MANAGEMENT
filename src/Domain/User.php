<?php

namespace Daver\MVC\Domain;

class User
{
  private string $id;
  private string $name;
  private string $password;

  public function getId(): string
  {
    return $this->id;
  }

  public function setId($id): User
  {
    $this->id = $id;
    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName($name): User
  {
    $this->name = $name;
    return $this;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword($password): User
  {
    $this->password = $password;
    return $this;
  }
}