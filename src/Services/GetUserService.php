<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\User;
use TijmenWierenga\Commenting\Repositories\UserRepository;

final class GetUserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UuidInterface $uuid): User
    {
        return $this->userRepository->find($uuid);
    }
}
