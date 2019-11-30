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
    private Commentable $root;
    private Commentable $commentable;

    private function __construct(
        UuidInterface $id,
        string $content,
        UuidInterface $authorId,
        Commentable $root,
        DateTimeImmutable $createdAt,
        Commentable $commentable
    ) {
        if (!in_array($root->resourceType(), static::RESOURCE_TYPES, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid resource type provided for comment (%s). Available types: %s',
                    $root->resourceType(),
                    implode(', ', static::RESOURCE_TYPES)
                )
            );
        }
        
        $this->id = $id;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->root = $root;
        $this->commentable = $commentable;
    }

    public static function newFor(Commentable $commentable, UuidInterface $authorId, string $content): self
    {
        return new self(
            Uuid::uuid4(),
            $content,
            $authorId,
            $commentable->getRoot(),
            new DateTimeImmutable(),
            $commentable
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

    public function getRoot(): Commentable
    {
        return $this->root;
    }
}
