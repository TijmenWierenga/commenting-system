<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Services\GetCommentService;

final class GetCommentAction
{
    private GetCommentService $getCommentService;

    public function __construct(GetCommentService $getCommentService)
    {
        $this->getCommentService = $getCommentService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $comment = ($this->getCommentService)(Uuid::fromString($args['id']));

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($comment, JSON_THROW_ON_ERROR, 512),
        );
    }
}
