<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Comment;

interface CommentRepository
{
    public function find(UuidInterface $id): Comment;
    public function findByArticleId(UuidInterface $id): iterable;
    public function save(Comment $comment): void;
}
