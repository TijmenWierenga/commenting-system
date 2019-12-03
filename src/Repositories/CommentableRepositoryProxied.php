<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Commentable;

final class CommentableRepositoryProxied implements CommentableRepository
{
    private array $repositoryMap = [];

    public function __construct(array $repositoryMap)
    {
        $this->repositoryMap = $repositoryMap;
    }

    public function find(string $type, UuidInterface $id): Commentable
    {
        $repository = $this->repositoryMap[$type];

        return $repository->find($id);
    }
}
