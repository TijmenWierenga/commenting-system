<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\User;

class UserRepositoryInMemory implements UserRepository
{
    /**
     * @var array|User[]
     */
    private array $users;

    public function __construct(User ...$users)
    {
        $this->users = $users;
    }

    public function exists(UuidInterface $id): bool
    {
        $results = count(
            array_filter(
                $this->users,
                fn (User $user): bool => $user->getId()->toString() === $id->toString()
            )
        );

        return $results > 0;
    }
}
