<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Services;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Repositories\CommentRepository;

final class GetCommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(UuidInterface $id): Comment
    {
        return $this->commentRepository->find($id);
    }
}
