<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions\Handlers;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Exceptions\Handler;

final class CatchAllHandler implements Handler
{
    public function handles(Exception $e): bool
    {
        return true;
    }

    public function handle(Exception $e): ResponseInterface
    {
        return new Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ], JSON_THROW_ON_ERROR, 512)
        );
    }
}
