<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use TijmenWierenga\Commenting\Services\SaveCommentService;

class SaveCommentAction
{
    private SaveCommentService $saveCommentService;

    public function __construct(SaveCommentService $saveCommentService)
    {
        $this->saveCommentService = $saveCommentService;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $resourceType = $body['resource']['type'];
        $resourceId = $body['resource']['id'];
        $authorId = $body['authorId'];
        $content = $body['content'];

        $comment = ($this->saveCommentService)($resourceType, $resourceId, $authorId, $content);

        return new Response(
            201,
            ['Content-Type' => 'application/json'],
            json_encode($comment, JSON_THROW_ON_ERROR, 512)
        );
    }
}
