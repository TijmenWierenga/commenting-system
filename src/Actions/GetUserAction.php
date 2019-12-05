<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Services\GetUserService;

final class GetUserAction
{
    private GetUserService $getUserService;

    public function __construct(GetUserService $getUserService)
    {
        $this->getUserService = $getUserService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $user = ($this->getUserService)(Uuid::fromString($args['id']));

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($user, JSON_THROW_ON_ERROR, 512),
        );
    }
}
