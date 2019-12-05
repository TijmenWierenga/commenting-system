<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Exceptions\Handlers;

use Exception;
use GuzzleHttp\Psr7\Response;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use League\OpenAPIValidation\Schema\Exception\TypeMismatch;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Exceptions\Handler;

final class ValidationHandler implements Handler
{
    public function handles(Exception $e): bool
    {
        return $e instanceof ValidationFailed;
    }

    public function handle(Exception $e): ResponseInterface
    {
        $data = [
            'message' => $e->getMessage(),
        ];

        $previous = $e->getPrevious();

        if ($previous instanceof KeywordMismatch) {
            $data = [
                'message' => $e->getMessage(),
                'error' => $previous->getMessage(),
                'keyword' => $previous->keyword(),
                'data' => $previous->data(),
                'breadCrumb' => implode('.', $previous->dataBreadCrumb()->buildChain() ?? [])
            ];
        }

        if ($previous instanceof TypeMismatch) {
            $data = [
                'message' => $e->getMessage(),
                'error' => $previous->getMessage(),
                'data' => $previous->data(),
                'breadCrumb' => implode('.', $previous->dataBreadCrumb()->buildChain() ?? [])
            ];
        }

        return new Response(
            400,
            ['Content-Type' => 'application/json'],
            json_encode($data, JSON_THROW_ON_ERROR, 512)
        );
    }
}
