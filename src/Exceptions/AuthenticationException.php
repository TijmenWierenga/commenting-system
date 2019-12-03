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
            'No credentials provided. 
            Please supply a "%s" header with a user UUID and a "%s" header with an API token',
            AuthManager::CLIENT_HEADER,
            AuthManager::TOKEN_HEADER
        ));
    }

    public static function invalidCredentials(): self
    {
        return new self('Invalid API Token');
    }
}
