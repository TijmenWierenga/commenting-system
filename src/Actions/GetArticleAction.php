<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Services\GetArticleService;

final class GetArticleAction
{
    private GetArticleService $getArticleService;

    public function __construct(GetArticleService $getArticleService)
    {
        $this->getArticleService = $getArticleService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $articleId = Uuid::fromString($args['id']);
        $article = ($this->getArticleService)($articleId);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($article, JSON_THROW_ON_ERROR, 512)
        );
    }
}
