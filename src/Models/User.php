<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class User implements JsonSerializable
{
    private UuidInterface $id;
    private string $username;
    private string $password;

    private function __construct(UuidInterface $id, string $username, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function new(string $username, string $password): self
    {
        return new static(Uuid::uuid4(), $username, $password);
    }

    public static function fromScalar(UuidInterface $id, string $username, string $password): self
    {
        return new static($id, $username, $password);
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

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->getId()->toString(),
            'username' => $this->getUsername()
        ];
    }
}
