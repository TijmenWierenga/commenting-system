<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Exception;

use Exception;
use GuzzleHttp\Psr7\Response;
use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TijmenWierenga\Commenting\Exceptions\ExceptionHandler;
use TijmenWierenga\Commenting\Exceptions\Handler;

final class ExceptionHandlerTest extends TestCase
{
    public function testItThrowsTheExceptionIfNoHandlerIsDefined(): void
    {
        $exception = new LogicException();
        $this->expectExceptionObject($exception);

        $handler = new class implements Handler {
            public function handles(Exception $e): bool
            {
                return $e instanceof RuntimeException;
            }

            public function handle(Exception $e): ResponseInterface
            {
                return new Response(500);
            }
        };
        $exceptionHandler = new ExceptionHandler($handler);

        $exceptionHandler($exception);
    }

    public function testItPassesTheResponseToTheSupportedHandler(): void
    {
        $response = new Response(500);
        $handler = new class ($response) implements Handler {
            private ResponseInterface $response;

            public function __construct(ResponseInterface $response)
            {
                $this->response = $response;
            }

            public function handles(Exception $e): bool
            {
                return $e instanceof RuntimeException;
            }

            public function handle(Exception $e): ResponseInterface
            {
                return $this->response;
            }
        };
        $exceptionHandler = new ExceptionHandler($handler);

        $result = $exceptionHandler(new RuntimeException());

        static::assertEquals($response, $result);
    }
}
