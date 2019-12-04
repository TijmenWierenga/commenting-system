<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use RuntimeException;

final class UsernameAlreadyTakenException extends RuntimeException
{
    public static function forUsername(string $username): self
    {
        return new self(sprintf('Username "%s" is already taken', $username));
    }
}
