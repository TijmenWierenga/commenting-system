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
    public const RESOURCE_TYPE_ARTICLE = 'article';
    public const RESOURCE_TYPE_COMMENT = 'comment';
    public const RESOURCE_TYPES = [self::RESOURCE_TYPE_ARTICLE, self::RESOURCE_TYPE_COMMENT];

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
        $this->validateCommentableType($rootId);
        $this->validateCommentableType($commentableId);
        
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
        );
    }

    public function resourceType(): string
    {
        return 'comment';
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

    private function validateCommentableType(CommentableId $commentableId): void
    {
        if (!in_array($commentableId->getResourceType(), static::RESOURCE_TYPES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid resource type provided for comment (%s). Available types: %s',
                    $commentableId->getResourceType(),
                    implode(', ', static::RESOURCE_TYPES)
                )
            );
        }
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
