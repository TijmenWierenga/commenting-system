<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Authentication;

use GuzzleHttp\Psr7\ServerRequest;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Exceptions\AuthenticationException;
use TijmenWierenga\Commenting\Hashing\PlainTextHasher;
use TijmenWierenga\Commenting\Repositories\UserRepositoryInMemory;

use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class AuthManagerTest extends TestCase
{
    public function testItThrowsAnErrorWhenNoTokenIsProvided(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectDeprecationMessage('No credentials provided. 
            Please supply an "Authorization" header with a valid access token');

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);
        $authManager = new AuthManager($userRepository, new PlainTextHasher(), 'secret-key');

        $request = new ServerRequest('GET', '/');

        $authManager->authenticate($request);
    }

    public function testItDoesNotAllowAnInvalidToken(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid access token');

        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);

        $accessToken = (new Builder())->identifiedBy($user->getId()->toString())
            ->expiresAt(time() + 10)
            ->getToken(new Sha256(), new Key('wrong-key'));

        $authManager = new AuthManager($userRepository, new PlainTextHasher(), 'secret-key');

        $request = new ServerRequest('GET', '/', [
            'Authorization' => (string) $accessToken
        ]);

        $authManager->authenticate($request);
    }

    public function testItSuccessfullyAuthenticates(): void
    {
        $user = make_user('tijmen');
        $userRepository = new UserRepositoryInMemory($user);

        $accessToken = (new Builder())->identifiedBy($user->getId()->toString())
            ->expiresAt(time() + 10)
            ->getToken(new Sha256(), new Key('secret-key'));

        $authManager = new AuthManager($userRepository, new PlainTextHasher(), 'secret-key');

        $request = new ServerRequest('GET', '/', [
            'Authorization' => (string) $accessToken
        ]);

        $authManager->authenticate($request);

        static::assertEquals($user, $authManager->getAuthenticatedUser());
    }

    public function testItDoesNotLoginWithWrongCredentials(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $user = make_user('tijmen'); // Default password is '123456'
        $userRepository = new UserRepositoryInMemory($user);

        $authManager = new AuthManager($userRepository, new PlainTextHasher(), 'secret-key');

        $authManager->login('tijmen', 'wrong-password');
    }

    public function testItReturnsATokenOnSuccessfulLogin(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials');

        $user = make_user('tijmen'); // Default password is '123456'
        $userRepository = new UserRepositoryInMemory($user);

        $authManager = new AuthManager($userRepository, new PlainTextHasher(), '123456');

        $token = $authManager->login('tijmen', 'wrong-password');

        static::assertEquals($user->getId()->toString(), $token->getClaim('jti'));
    }
}
