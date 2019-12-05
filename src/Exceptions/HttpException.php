<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use RuntimeException;

class HttpException extends RuntimeException
{
    private int $statusCode;

    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
