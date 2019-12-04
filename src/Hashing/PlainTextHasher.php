<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Hashing;

final class PlainTextHasher implements Hasher
{
    public function hash(string $input): string
    {
        return $input;
    }

    public function verify(string $input, string $hash): bool
    {
        return $input === $hash;
    }
}
