<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

interface ArticleRepository
{
    public function find(UuidInterface $id): Article;
}
