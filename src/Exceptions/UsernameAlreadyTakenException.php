<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

final class UsernameAlreadyTakenException extends HttpException
{
    public static function forUsername(string $username): self
    {
        return new self(400, sprintf('Username "%s" is already taken', $username));
    }
}
