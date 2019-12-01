<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Repositories\CommentableRepository;
use TijmenWierenga\Commenting\Repositories\CommentRepository;
use TijmenWierenga\Commenting\Repositories\UserRepository;

final class SaveCommentAction
{
    private CommentableRepository $commentableRepository;
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;

    public function __construct(
        CommentableRepository $commentableRepository,
        CommentRepository $commentRepository,
        UserRepository $userRepository
    ) {
        $this->commentableRepository = $commentableRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $resourceType, string $resourceId, string $authorId, string $content): Comment
    {
        $authorId = Uuid::fromString($authorId);

        if (!$this->userRepository->exists($authorId)) {
            throw new InvalidArgumentException(
                sprintf('User with ID "%s" does not exist', $authorId->toString())
            );
        }

        try {
            $commentable = $this->commentableRepository->find($resourceType, Uuid::fromString($resourceId));
        } catch (ModelNotFoundException $e) {
            throw new InvalidArgumentException($e->getMessage(), 1, $e);
        }

        $comment = Comment::newFor($commentable, $authorId, $content);
        $this->commentRepository->save($comment);

        return $comment;
    }
}
