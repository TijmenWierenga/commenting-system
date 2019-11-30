<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

final class Comment
{
    public const RESOURCE_TYPE_ARTICLE = 'article';
    public const RESOURCE_TYPE_COMMENT = 'comment';
    public const RESOURCE_TYPES = [self::RESOURCE_TYPE_ARTICLE, self::RESOURCE_TYPE_COMMENT];
    private UuidInterface $id;
    private string $content;
    private UuidInterface $authorId;
    private string $resourceType;
    private UuidInterface $resourceId;

    private function __construct(
        UuidInterface $id,
        string $content,
        UuidInterface $authorId,
        string $resourceType,
        UuidInterface $resourceId
    ) {
        if (!in_array($resourceType, static::RESOURCE_TYPES, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid resource type provided for comment (%s). Available types: %s',
                    $resourceType,
                    implode(', ', static::RESOURCE_TYPES)
                )
            );
        }
        
        $this->id = $id;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;
    }
}
