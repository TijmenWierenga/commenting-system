<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use Ramsey\Uuid\UuidInterface;

final class User
{
    private UuidInterface $id;
    private string $username;
    private string $apiToken;

    public function __construct(UuidInterface $id, string $username, string $apiToken)
    {
        $this->id = $id;
        $this->username = $username;
        $this->apiToken = $apiToken;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }
}
