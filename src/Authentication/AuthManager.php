<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Authentication;

use Lcobucci\JWT\{Builder, Parser, Token, ValidationData};
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Exceptions\{AuthenticationException, ModelNotFoundException};
use TijmenWierenga\Commenting\Hashing\Hasher;
use TijmenWierenga\Commenting\Models\User;
use TijmenWierenga\Commenting\Repositories\UserRepository;

final class AuthManager
{
    public const TOKEN_HEADER = 'Authorization';

    private UserRepository $userRepository;
    private ?User $authenticatedUser;
    private Hasher $hasher;
    private string $secretKey;
    private Sha256 $signer;

    public function __construct(UserRepository $userRepository, Hasher $hasher, string $secretKey)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->signer = new Sha256();
        $this->secretKey = $secretKey;
    }

    public function authenticate(ServerRequestInterface $request): void
    {
        if (!$request->hasHeader(static::TOKEN_HEADER)) {
            throw AuthenticationException::missingCredentials();
        }

        $accessToken = explode(' ', $request->getHeader(static::TOKEN_HEADER)[0])[1];

        try {
            $token = (new Parser())->parse($accessToken);

            if (!$token->validate(new ValidationData())) {
                throw AuthenticationException::invalidToken();
            }

            if (!$token->verify($this->signer, $this->secretKey)) {
                throw AuthenticationException::invalidToken();
            }

            $userId = $token->getClaim('jti');
            $user = $this->userRepository->find(Uuid::fromString($userId));
        } catch (ModelNotFoundException $e) {
            throw AuthenticationException::invalidToken();
        }

        $this->setAuthenticatedUser($user);
    }

    public function login(string $username, string $password): Token
    {
        try {
            $user = $this->userRepository->findByUsername($username);

            if (!$this->hasher->verify($password, $user->getPassword())) {
                throw AuthenticationException::invalidCredentials();
            }
        } catch (ModelNotFoundException $e) {
            throw AuthenticationException::invalidCredentials();
        }

        return (new Builder())
            ->identifiedBy($user->getId()->toString())
            ->expiresAt(time() + 3600)
            ->issuedAt(time())
            ->getToken($this->signer, new Key($this->secretKey));
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
