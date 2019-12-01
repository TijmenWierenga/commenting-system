<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Commentable;

interface CommentableRepository
{
    public function find(string $type, UuidInterface $id): Commentable;
}
