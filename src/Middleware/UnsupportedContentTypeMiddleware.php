<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UnsupportedContentTypeMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['GET', 'DELETE', 'OPTIONS', 'HEAD'])) {
            return $handler->handle($request);
        }

        if ($request->getHeader('Content-Type')[0] !== 'application/json') {
            return new Response(
                415,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'message' => 'Unsupported Media Type'
                ], JSON_THROW_ON_ERROR, 512)
            );
        }

        return $handler->handle($request);
    }
}
