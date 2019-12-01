<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Article;
use TijmenWierenga\Commenting\Repositories\ArticleRepository;
use TijmenWierenga\Commenting\Repositories\CommentRepository;

final class GetCommentsForArticleAction
{
    private CommentRepository $commentRepository;
    private ArticleRepository $articleRepository;

    public function __construct(CommentRepository $commentRepository, ArticleRepository $articleRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->articleRepository = $articleRepository;
    }

    public function __invoke(UuidInterface $articleId): iterable
    {
        if (!$this->articleRepository->exists($articleId)) {
            throw new ModelNotFoundException(Article::class, $articleId->toString());
        }

        return $this->commentRepository->findByArticleId($articleId);
    }
}
