<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
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
        $results = count($this->filterById($id));

        return $results > 0;
    }

    public function find(UuidInterface $uuid): User
    {
        $results = $this->filterById($uuid);

        if (!count($results)) {
            throw new ModelNotFoundException(User::class, $uuid->toString());
        }

        return reset($results);
    }

    public function findByUsername(string $username): User
    {
        $results = array_filter(
            $this->users,
            fn (User $user): bool => $user->getUsername() === $username
        );

        if (!count($results)) {
            throw new ModelNotFoundException(User::class, $username);
        }

        return reset($results);
    }

    private function filterById(UuidInterface $uuid): array
    {
        return array_filter(
            $this->users,
            fn (User $user): bool => $user->getId()->toString() === $uuid->toString()
        );
    }

    public function save(User $user): void
    {
        if ($this->exists($user->getId())) {
            $this->users = array_filter(
                $this->users,
                fn (User $item): bool => $item->getId()->toString() !== $user->getId()->toString()
            ); // Remove existing user from collection
        }

        $this->users[] = $user;
    }
}
