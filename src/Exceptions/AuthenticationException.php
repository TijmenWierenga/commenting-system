<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use RuntimeException;
use TijmenWierenga\Commenting\Authentication\AuthManager;

final class AuthenticationException extends RuntimeException
{
    public static function missingCredentials(): self
    {
        return new self(sprintf(
            'No credentials provided. Please supply an "%s" header with a valid access token',
            AuthManager::TOKEN_HEADER
        ));
    }

    public static function invalidCredentials(): self
    {
        return new self('Invalid credentials');
    }

    public static function invalidToken(): self
    {
        return new self('Invalid access token');
    }
}
