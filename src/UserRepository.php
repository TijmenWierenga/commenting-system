<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function exists(UuidInterface $id): bool;
}
