<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

interface CommentableRepository
{
    public function find(string $type, UuidInterface $id): Commentable;
}
