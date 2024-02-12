<?php

namespace App\entitys;

class UserEntity
{
    private function __construct(
        private string $name,
        private string $email,
        private string $password,
        private ?int $id = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public static function create(
        string $name,
        string $email,
        string $password
    ): UserEntity {
       return new self($name, $email, $password);
    }
}