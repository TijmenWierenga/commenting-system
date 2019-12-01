<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use RuntimeException;

class ModelNotFoundException extends RuntimeException
{
    public function __construct(string $fqcn, string $id)
    {
        parent::__construct(sprintf('Model "%s" with ID "%s" was not found'), $fqcn, $id);
    }
}
