<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions\Handlers;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Exceptions\Handler;
use TijmenWierenga\Commenting\Exceptions\HttpException;

final class HttpExceptionHandler implements Handler
{
    public function handles(Exception $e): bool
    {
        return $e instanceof HttpException;
    }

    /**
     * @param  HttpException  $e
     * @return ResponseInterface
     */
    public function handle(Exception $e): ResponseInterface
    {
        return new Response(
            $e->getStatusCode(),
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => $e->getMessage()
            ], JSON_THROW_ON_ERROR, 512)
        );
    }
}
