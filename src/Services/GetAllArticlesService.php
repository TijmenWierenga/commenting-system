<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use TijmenWierenga\Commenting\Models\Article;
use TijmenWierenga\Commenting\Repositories\ArticleRepository;

final class GetAllArticlesService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return array|Article[]
     */
    public function __invoke(): array
    {
        return $this->articleRepository->getAll();
    }
}
