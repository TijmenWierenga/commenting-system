<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Exceptions\AuthenticationException;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Hashing\Hasher;
use TijmenWierenga\Commenting\Models\User;
use TijmenWierenga\Commenting\Repositories\UserRepository;

final class AuthManager
{
    public const TOKEN_HEADER = 'X-Api-Token';
    public const CLIENT_HEADER = 'X-Client-Id';

    private UserRepository $userRepository;
    private User $authenticatedUser;
    private Hasher $hasher;

    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function authenticate(ServerRequestInterface $request): void
    {
        if (!$request->hasHeader(static::TOKEN_HEADER) || !$request->hasHeader(static::CLIENT_HEADER)) {
            throw AuthenticationException::missingCredentials();
        }

        $clientId = $request->getHeader(static::CLIENT_HEADER)[0];
        $apiToken = $request->getHeader(static::TOKEN_HEADER)[0];

        try {
            $user = $this->userRepository->find(Uuid::fromString($clientId));

            if (!$this->hasher->verify($apiToken, $user->getApiToken())) {
                throw AuthenticationException::invalidCredentials();
            }
        } catch (ModelNotFoundException $e) {
            throw AuthenticationException::invalidCredentials();
        }

        $this->setAuthenticatedUser($user);
    }

    public function getAuthenticatedUser(): ?User
    {
        return $this->authenticatedUser;
    }

    private function setAuthenticatedUser(User $user): void
    {
        $this->authenticatedUser = $user;
    }
}
