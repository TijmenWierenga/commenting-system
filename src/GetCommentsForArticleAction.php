<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

final class GetCommentsForArticleAction
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(UuidInterface $articleId): iterable
    {
        // TODO: Verify if article exists

        return $this->commentRepository->findByArticleId($articleId);
    }
}
