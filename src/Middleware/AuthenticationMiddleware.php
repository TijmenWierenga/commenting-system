<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TijmenWierenga\Commenting\Authentication\AuthManager;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * @var AuthManager
     */
    private AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->authManager->authenticate($request);

        return $handler->handle($request);
    }
}
