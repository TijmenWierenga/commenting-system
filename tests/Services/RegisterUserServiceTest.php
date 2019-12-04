<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Services;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Exceptions\UsernameAlreadyTakenException;
use TijmenWierenga\Commenting\Hashing\Hasher;
use TijmenWierenga\Commenting\Hashing\PlainTextHasher;
use TijmenWierenga\Commenting\Repositories\UserRepositoryInMemory;
use TijmenWierenga\Commenting\Services\RegisterUserService;

use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class RegisterUserServiceTest extends TestCase
{
    public function testItDoesNotAllowATakenUsername(): void
    {
        $this->expectExceptionObject(UsernameAlreadyTakenException::forUsername('tijmen'));
        $userRepository = new UserRepositoryInMemory(make_user('tijmen'));
        $service = new RegisterUserService($userRepository, new PlainTextHasher());

        $service('tijmen', 'password');
    }

    public function testItRegistersANewUser(): void
    {
        $userRepository = new UserRepositoryInMemory(make_user('tijmen'));
        $hasher = new class implements Hasher {
            public function hash(string $input): string
            {
                return 'hashed-password';
            }

            public function verify(string $input, string $hash): bool
            {
                return $input === 'hashed-password';
            }
        };
        $service = new RegisterUserService($userRepository, $hasher);

        $user = $service('paul', 'plain-text-password');

        static::assertEquals('paul', $user->getUsername());
        static::assertEquals('hashed-password', $user->getPassword());
    }
}
