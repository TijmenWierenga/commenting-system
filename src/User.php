<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

final class User
{
    private UuidInterface $id;
    private string $username;

    public function __construct(UuidInterface $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
