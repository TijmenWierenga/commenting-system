<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

interface CommentRepository
{
    public function find(UuidInterface $id): Comment;
    public function findByArticleId(UuidInterface $id): iterable;
    public function save(Comment $comment): void;
}
