<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting\Hashing;

interface Hasher
{
    public function hash(string $input): string;
    public function verify(string $input, string $hash): bool;
}
