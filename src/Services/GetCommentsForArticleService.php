<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\{Article, Comment};
use TijmenWierenga\Commenting\Repositories\{ArticleRepository, CommentRepository};

class GetCommentsForArticleService
{
    private ArticleRepository $articleRepository;
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository, ArticleRepository $articleRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return iterable|Comment[]
     */
    public function __invoke(UuidInterface $articleId): iterable
    {
        if (!$this->articleRepository->exists($articleId)) {
            throw new ModelNotFoundException(Article::class, $articleId->toString());
        }

        return $this->commentRepository->findByArticleId($articleId);
    }
}
