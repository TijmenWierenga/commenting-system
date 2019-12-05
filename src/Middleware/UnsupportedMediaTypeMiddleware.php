<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UnsupportedMediaTypeMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('Accept')) {
            $requestedMediaType = $request->getHeader('Accept')[0];

            if ($requestedMediaType !== 'application/json' && $requestedMediaType !== '*/*') {
                return new Response(
                    406,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'message' => 'Unsupported media type'
                    ], JSON_THROW_ON_ERROR, 512)
                );
            }
        }

        return $handler->handle($request);
    }
}
