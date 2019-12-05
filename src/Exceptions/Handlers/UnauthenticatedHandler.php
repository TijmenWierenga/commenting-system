<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions\Handlers;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Exceptions\AuthenticationException;
use TijmenWierenga\Commenting\Exceptions\Handler;

final class UnauthenticatedHandler implements Handler
{
    public function handles(Exception $e): bool
    {
        return $e instanceof AuthenticationException;
    }

    public function handle(Exception $e): ResponseInterface
    {
        return new Response(
            401,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => $e->getMessage(),
            ], JSON_THROW_ON_ERROR, 512)
        );
    }
}
