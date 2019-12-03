<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CommentableId
{
    public const RESOURCE_TYPE_ARTICLE = 'article';
    public const RESOURCE_TYPE_COMMENT = 'comment';
    public const RESOURCE_TYPES = [self::RESOURCE_TYPE_ARTICLE, self::RESOURCE_TYPE_COMMENT];

    private string $type;
    private UuidInterface $uuid;

    private function __construct(string $type, UuidInterface $uuid)
    {
        if (!in_array($type, static::RESOURCE_TYPES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid resource type provided for comment (%s). Available types: %s',
                    $type,
                    implode(', ', static::RESOURCE_TYPES)
                )
            );
        }

        $this->type = $type;
        $this->uuid = $uuid;
    }

    public static function new(string $type): self
    {
        return new static($type, Uuid::uuid4());
    }

    public static function fromScalar(string $type, string $uuid): self
    {
        return new static($type, Uuid::fromString($uuid));
    }

    public function getResourceType(): string
    {
        return $this->type;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->getUuid()->toString();
    }
}
