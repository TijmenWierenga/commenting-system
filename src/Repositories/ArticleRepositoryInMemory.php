<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Article;

class ArticleRepositoryInMemory implements ArticleRepository
{
    /**
     * @var array|Article[]
     */
    private array $articles;

    public function __construct(Article ...$articles)
    {
        $this->articles = $articles;
    }

    public function find(UuidInterface $id): Article
    {
        $results = array_filter(
            $this->articles,
            fn (Article $article): bool => $article->getId()->toString() === $id->toString()
        );

        if (!count($results)) {
            throw new ModelNotFoundException(Article::class, $id->toString());
        }

        return $results[0];
    }

    public function exists(UuidInterface $id): bool
    {
        try {
            $this->find($id);
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->articles;
    }
}
