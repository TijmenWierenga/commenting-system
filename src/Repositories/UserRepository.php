<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;

interface UserRepository
{
    public function exists(UuidInterface $id): bool;
}
