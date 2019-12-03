<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use PDO;
use Ramsey\Uuid\UuidInterface;

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
}
