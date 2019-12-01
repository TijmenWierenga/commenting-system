<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use Ramsey\Uuid\UuidInterface;
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
            // Throw an exception that can result in a 404
        }

        return $this->commentRepository->findByArticleId($articleId);
    }
}
