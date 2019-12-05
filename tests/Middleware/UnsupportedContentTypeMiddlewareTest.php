<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TijmenWierenga\Commenting\Middleware\UnsupportedContentTypeMiddleware;

final class UnsupportedContentTypeMiddlewareTest extends TestCase
{
    /**
     * @dataProvider emptyBodyRequestMethods
     */
    public function testItAllowsMethodsWithoutBody(string $method): void
    {
        $request = new ServerRequest($method, '/');

        $middleware = new UnsupportedContentTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(200, $response->getStatusCode());
    }

    public function testItAllowsApplicationJsonAsContentType(): void
    {
        $request = new ServerRequest('POST', '/', ['Content-Type' => 'application/json']);

        $middleware = new UnsupportedContentTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(200, $response->getStatusCode());
    }

    public function testItDoesNotAllowUnacceptableContentTypes(): void
    {
        $request = new ServerRequest('POST', '/', ['Content-Type' => 'application/xml']);

        $middleware = new UnsupportedContentTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(415, $response->getStatusCode());
    }

    public function emptyBodyRequestMethods(): array
    {
        return array_map(fn (string $method): array => [$method], [
            'GET',
            'DELETE',
            'OPTIONS',
            'HEAD'
        ]);
    }
}
