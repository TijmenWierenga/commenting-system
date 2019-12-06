<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Article;

interface ArticleRepository
{
    public function find(UuidInterface $id): Article;
    /**
     * @return array|Article[]
     */
    public function getAll(): array;
    public function exists(UuidInterface $id): bool;
}
