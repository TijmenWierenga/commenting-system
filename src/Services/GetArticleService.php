<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Article;
use TijmenWierenga\Commenting\Repositories\ArticleRepository;

final class GetArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function __invoke(UuidInterface $articleId): Article
    {
        return $this->articleRepository->find($articleId);
    }
}
