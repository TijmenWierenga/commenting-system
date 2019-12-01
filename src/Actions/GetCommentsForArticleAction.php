<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Services\GetCommentsForArticleService;

final class GetCommentsForArticleAction
{
    /**
     * @var GetCommentsForArticleService
     */
    private GetCommentsForArticleService $commentService;

    public function __construct(GetCommentsForArticleService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $articleId = Uuid::fromString($args['id']);

        $comments = ($this->commentService)($articleId);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($comments, JSON_THROW_ON_ERROR, 512)
        );
    }
}
