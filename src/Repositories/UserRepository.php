<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\User;

interface UserRepository
{
    public function exists(UuidInterface $id): bool;
    public function find(UuidInterface $uuid): User;
    public function findByUsername(string $username): User;
}
