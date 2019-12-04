<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Exceptions\UsernameAlreadyTakenException;
use TijmenWierenga\Commenting\Hashing\Hasher;
use TijmenWierenga\Commenting\Models\User;
use TijmenWierenga\Commenting\Repositories\UserRepository;

final class RegisterUserService
{
    private UserRepository $userRepository;
    /**
     * @var Hasher
     */
    private Hasher $hasher;

    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function __invoke(string $username, string $password): User
    {
        try {
            $this->userRepository->findByUsername($username);
            throw UsernameAlreadyTakenException::forUsername($username);
        } catch (ModelNotFoundException $e) {
            // Username is not taken. Continue the flow
        }

        $hashedPassword = $this->hasher->hash($password);
        $user = User::new($username, $hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
