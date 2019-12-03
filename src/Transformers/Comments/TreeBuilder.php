<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Transformers\Comments;

use TijmenWierenga\Commenting\Models\Comment;

final class TreeBuilder
{
    /**
     * @var array|Comment[]
     */
    private array $comments;

    public function transform(Comment ...$comments): array
    {
        $this->comments = $comments;

        $rootId = $comments[0]->getRootId();
        $rootComments = array_filter(
            $comments,
            fn (Comment $item) => $item->getBelongsToId()->toString() === $rootId->toString()
        );

        return array_values(array_map([$this, 'createComment'], $rootComments));
    }

    private function createComment(Comment $comment): array
    {
        $childComments = array_filter(
            $this->comments,
            fn (Comment $item): bool => $comment->getId()->toString() === $item->getBelongsToId()->toString()
        );

        $comments = array_values(array_map([$this, 'createComment'], $childComments));

        return [
            'uuid' => $comment->getId()->toString(),
            'content' => $comment->getContent(),
            'authorId' => $comment->getAuthorId()->toString(),
            'comments' => $comments
        ];
    }
}
