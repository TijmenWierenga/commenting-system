<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions\Handlers;

use Exception;
use GuzzleHttp\Psr7\Response;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Exceptions\Handler;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;

final class NotFoundHandler implements Handler
{
    public function handles(Exception $e): bool
    {
        return in_array(get_class($e), [NotFoundException::class, ModelNotFoundException::class], true);
    }

    public function handle(Exception $e): ResponseInterface
    {
        return new Response(
            404,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => $e->getMessage(),
            ], JSON_THROW_ON_ERROR, 512)
        );
    }
}
