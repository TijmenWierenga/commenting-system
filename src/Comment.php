<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Comment implements Commentable
{
    public const RESOURCE_TYPE_ARTICLE = 'article';
    public const RESOURCE_TYPE_COMMENT = 'comment';
    public const RESOURCE_TYPES = [self::RESOURCE_TYPE_ARTICLE, self::RESOURCE_TYPE_COMMENT];

    private UuidInterface $id;
    private string $content;
    private UuidInterface $authorId;
    private DateTimeImmutable $createdAt;
    private Commentable $commentable;

    private function __construct(
        UuidInterface $id,
        string $content,
        UuidInterface $authorId,
        Commentable $commentable,
        DateTimeImmutable $createdAt
    ) {
        if (!in_array($commentable->resourceType(), static::RESOURCE_TYPES, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid resource type provided for comment (%s). Available types: %s',
                    $commentable->resourceType(),
                    implode(', ', static::RESOURCE_TYPES)
                )
            );
        }
        
        $this->id = $id;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->commentable = $commentable;
    }

    public static function newFor(Commentable $commentable, UuidInterface $authorId, string $content): self
    {
        return new self(
            Uuid::uuid4(),
            $content,
            $authorId,
            $commentable,
            new DateTimeImmutable()
        );
    }

    public function resourceType(): string
    {
        return 'comment';
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function belongsTo(): Commentable
    {
        return $this->commentable;
    }
}
