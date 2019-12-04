<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use Ramsey\Uuid\UuidInterface;

final class User
{
    private UuidInterface $id;
    private string $username;
    private string $apiToken;
    private string $password;

    public function __construct(UuidInterface $id, string $username, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
