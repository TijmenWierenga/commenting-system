<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Hashing;

final class Argon2IdHasher implements Hasher
{
    private int $timeCost;

    public function __construct(int $timeCost = PASSWORD_ARGON2_DEFAULT_TIME_COST)
    {
        $this->timeCost = $timeCost;
    }

    public function hash(string $input): string
    {
        return password_hash($input, PASSWORD_ARGON2ID, [
            'time_cost' => $this->timeCost
        ]);
    }

    public function verify(string $input, string $hash): bool
    {
        return password_verify($input, $hash);
    }
}
