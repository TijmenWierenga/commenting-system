<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\User;

final class UserRepositorySql implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function exists(UuidInterface $id): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM `users` WHERE `uuid` = :uuid');
        $statement->execute([
            'uuid' => $id->toString()
        ]);

        $result = $statement->fetchColumn();

        return (bool) $result;
    }

    public function find(UuidInterface $uuid): User
    {
        $statement = $this->pdo->prepare('SELECT * FROM `users` WHERE `uuid` = :uuid');
        $statement->execute([
            'uuid' => $uuid->toString()
        ]);

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new ModelNotFoundException(User::class, $uuid->toString());
        }

        return new User(
            Uuid::fromString($data['uuid']),
            $data['username'],
            $data['password'],
        );
    }

    public function findByUsername(string $username): User
    {
        $statement = $this->pdo->prepare('SELECT * FROM `users` WHERE `username` = :username');
        $statement->execute([
            'username' => $username
        ]);

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new ModelNotFoundException(User::class, $username);
        }

        return new User(
            Uuid::fromString($data['uuid']),
            $data['username'],
            $data['password'],
        );
    }
}
