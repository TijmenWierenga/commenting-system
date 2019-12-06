<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Commenting\Services\GetAllArticlesService;

final class GetAllArticlesAction
{
    private GetAllArticlesService $getAllArticlesService;

    public function __construct(GetAllArticlesService $getAllArticlesService)
    {
        $this->getAllArticlesService = $getAllArticlesService;
    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $articles = ($this->getAllArticlesService)();

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($articles, JSON_THROW_ON_ERROR, 512),
        );
    }
}
