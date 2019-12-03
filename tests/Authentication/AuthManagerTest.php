<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Authentication;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Exceptions\AuthenticationException;
use TijmenWierenga\Commenting\Hashing\PlainTextHasher;
use TijmenWierenga\Commenting\Repositories\UserRepositoryInMemory;

use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class AuthManagerTest extends TestCase
{
    public function testItThrowsAnErrorWhenNoCredentialsAreProvided(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/');

        $authManager->authenticate($request);
    }

    public function testItThrowsAnErrorWhenTheApiTokenIsMissing(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/', [
            'X-Client-Id' => $user->getId()->toString()
        ]);

        $authManager->authenticate($request);
    }

    public function testItThrowsAnErrorWhenTheClientIdIsMissing(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/', [
            'X-Api-Token' => 'fixed-api-token'
        ]);

        $authManager->authenticate($request);
    }

    public function testItDoesNotAllowAWrongClientId(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/', [
            'X-Client-Id' => '3299368b-eea5-4b59-b91b-a33d1ce81384', // Random UUID
            'X-Api-Token' => 'fixed-api-token'
        ]);

        $authManager->authenticate($request);
    }

    public function testItDoesNotAllowAWrongApiToken(): void
    {
        $this->expectException(AuthenticationException::class);

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/', [
            'X-Client-Id' => $user->getId()->toString(),
            'X-Api-Token' => 'wrong-api-token' // Wrong token
        ]);

        $authManager->authenticate($request);
    }

    public function testItSuccessfullyAuthenticates(): void
    {
        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher());

        $request = new ServerRequest('GET', '/', [
            'X-Client-Id' => $user->getId()->toString(),
            'X-Api-Token' => 'fixed-api-token' // Wrong token
        ]);

        $authManager->authenticate($request);

        static::assertEquals($user, $authManager->getAuthenticatedUser());
    }
}
