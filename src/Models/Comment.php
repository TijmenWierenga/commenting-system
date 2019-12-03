<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Comment implements Commentable, JsonSerializable
{
    private CommentableId $id;
    private string $content;
    private UuidInterface $authorId;
    private DateTimeImmutable $createdAt;
    private CommentableId $rootId;
    private CommentableId $commentableId;

    private function __construct(
        CommentableId $id,
        string $content,
        UuidInterface $authorId,
        CommentableId $rootId,
        CommentableId $commentableId,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->rootId = $rootId;
        $this->commentableId = $commentableId;
    }

    public static function newFor(Commentable $commentable, UuidInterface $authorId, string $content): self
    {
        return new self(
            CommentableId::new('comment'),
            $content,
            $authorId,
            $commentable->getRootId(),
            $commentable->getId(),
            new DateTimeImmutable(),
        );
    }

    public static function fromScalar(array $data): self
    {
        return new self(
            CommentableId::fromScalar('comment', $data['uuid']),
            $data['content'],
            Uuid::fromString($data['author_id']),
            CommentableId::fromScalar($data['root_type'], $data['root_id']),
            CommentableId::fromScalar($data['commentable_type'], $data['commentable_id']),
            new DateTimeImmutable($data['created_at'])
        );
    }

    public function getId(): CommentableId
    {
        return $this->id;
    }

    public function belongsToId(): CommentableId
    {
        return $this->commentableId;
    }


    public function getRootId(): CommentableId
    {
        return $this->rootId;
    }

    public function getAuthorId(): UuidInterface
    {
        return $this->authorId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId()->toString(),
            'authorId' => $this->authorId->toString(),
            'content' => $this->content,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
            'root' => [
                'id' => $this->getRootId()->getUuid()->toString(),
                'type' => $this->getRootId()->getResourceType(),
            ],
            'belongsTo' => [
                'id' => $this->belongsToId()->getUuid()->toString(),
                'type' => $this->belongsToId()->getResourceType()
            ],
        ];
    }
}
