<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TijmenWierenga\Commenting\Authentication\AuthManager;

final class LoginAction
{
    private AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $username = $body['username'];
        $password = $body['password'];

        $token = $this->authManager->login($username, $password);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['token' => (string)$token], JSON_THROW_ON_ERROR, 512)
        );
    }
}
