<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TijmenWierenga\Commenting\Services\RegisterUserService;

final class RegisterUserAction
{
    private RegisterUserService $registerUserService;

    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $user = ($this->registerUserService)($data['username'], $data['password']);

        return new Response(
            201,
            ['Content-Type' => 'application/json'],
            json_encode($user, JSON_THROW_ON_ERROR, 512),
        );
    }
}
