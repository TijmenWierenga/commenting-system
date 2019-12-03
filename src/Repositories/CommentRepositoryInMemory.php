<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Comment;

class CommentRepositoryInMemory implements CommentRepository
{
    /**
     * @var array|Comment[]
     */
    private array $comments;

    public function __construct(Comment ...$comments)
    {
        $this->comments = $comments;
    }

    public function find(UuidInterface $id): Comment
    {
        $results = $this->filterBy(fn (Comment $comment): bool => $comment->getId()->toString() === $id->toString());

        if (!count($results)) {
            throw new ModelNotFoundException(Comment::class, $id->toString());
        }

        return $results[0];
    }

    public function findByArticleId(UuidInterface $id): iterable
    {
        return $this->filterBy(
            fn (Comment $comment): bool =>
            $comment->getRootId()->getResourceType() === 'article'
            && $comment->getRootId()->toString() === $id->toString()
        );
    }

    public function save(Comment $comment): void
    {
        $newCollection = $this->filterBy(
            fn (Comment $item): bool => $comment->getId()->toString() !== $item->getId()->toString()
        );
        $newCollection[] = $comment;

        $this->comments = $newCollection;
    }

    /**
     * @param callable $filter
     * @return array|Comment[]
     */
    private function filterBy(callable $filter): array
    {
        return array_filter($this->comments, $filter);
    }
}
