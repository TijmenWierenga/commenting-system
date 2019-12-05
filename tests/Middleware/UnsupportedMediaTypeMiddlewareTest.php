<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TijmenWierenga\Commenting\Middleware\UnsupportedMediaTypeMiddleware;

final class UnsupportedMediaTypeMiddlewareTest extends TestCase
{
    public function testItPassesTheRequestToTheNextHandlerWhenNoAcceptHeaderIsPresent(): void
    {
        $request = new ServerRequest('GET', '/');

        $middleware = new UnsupportedMediaTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider validMediaTypeProvider
     */
    public function testItAllowsAcceptableMediaFormats(string $mediaType): void
    {
        $request = new ServerRequest('GET', '/', ['Accept' => $mediaType]);

        $middleware = new UnsupportedMediaTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider invalidMediaTypeProvider
     */
    public function testItDoesNotAllowInvalidMediaTypes(string $mediaType): void
    {
        $request = new ServerRequest('GET', '/', ['Accept' => $mediaType]);

        $middleware = new UnsupportedMediaTypeMiddleware();
        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response(200);
            }
        };

        $response = $middleware->process($request, $handler);

        static::assertEquals(406, $response->getStatusCode());
    }

    public function validMediaTypeProvider(): array
    {
        return array_map(fn (string $mediaType): array => [$mediaType], [
            'application/json',
            '*/*'
        ]);
    }

    public function invalidMediaTypeProvider(): array
    {
        return array_map(fn (string $mediaType): array => [$mediaType], [
            'application/xml',
            'text/plain'
        ]);
    }
}
