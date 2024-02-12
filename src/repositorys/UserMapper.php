<?php

namespace App\repositorys;

use App\entitys\UserEntity;

class UserMapper
{
    public function __construct(
        private \PDO $connection
    ) {}

    public function save(UserEntity $userEntity): void
    {
        $stmt = $this->connection->prepare(
            "
                INSERT INTO users(name, email, password)
                VALUES(:name, :email, :password)
            "
        );

        $stmt->execute([
            ':name' => $userEntity->getName(),
            ':email' => $userEntity->getEmail(),
            ':password' => $userEntity->getPassword()
        ]);

        $userEntity->setId($this->connection->lastInsertId());
    }
}