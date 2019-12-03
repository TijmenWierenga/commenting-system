<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Services\GetCommentsForArticleService;
use TijmenWierenga\Commenting\Transformers\Comments\TreeBuilder;

final class GetCommentsForArticleAction
{
    private GetCommentsForArticleService $commentService;
    private TreeBuilder $treeBuilder;

    public function __construct(GetCommentsForArticleService $commentService, TreeBuilder $treeBuilder)
    {
        $this->commentService = $commentService;
        $this->treeBuilder = $treeBuilder;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $articleId = Uuid::fromString($args['id']);

        $comments = ($this->commentService)($articleId);
        $tree = $this->treeBuilder->transform(...$comments);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($tree, JSON_THROW_ON_ERROR, 512)
        );
    }
}
