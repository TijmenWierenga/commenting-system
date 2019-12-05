<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

interface Handler
{
    public function handles(Exception $e): bool;
    public function handle(Exception $e): ResponseInterface;
}
