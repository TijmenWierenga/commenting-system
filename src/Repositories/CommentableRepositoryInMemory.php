<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Commentable;

class CommentableRepositoryInMemory implements CommentableRepository
{
    private array $collection = [];

    public function __construct(Commentable ...$commentables)
    {
        foreach ($commentables as $commentable) {
            $this->collection[$commentable->resourceType()][] = $commentable;
        }
    }

    public function find(string $type, UuidInterface $id): Commentable
    {
        if (!array_key_exists($type, $this->collection)) {
            throw new ModelNotFoundException($type, $id->toString());
        }

        $results = array_filter(
            $this->collection[$type],
            fn (Commentable $commentable) => $commentable->getId()->toString() === $id->toString()
        );

        if (!count($results)) {
            throw new ModelNotFoundException($type, $id->toString());
        }

        return $results[0];
    }
}
