<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Hashing;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Hashing\Argon2IdHasher;

final class Argon2IdHasherTest extends TestCase
{
    public function testItCreatesAndVerifiesAHash(): void
    {
        $input = 'my-password';
        $hasher = new Argon2IdHasher(1);

        $hash = $hasher->hash($input);

        static::assertTrue($hasher->verify('my-password', $hash));
        static::assertFalse($hasher->verify('wrong-password', $hash));
    }
}
