<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class CommentableId
{
    private string $type;
    private UuidInterface $uuid;

    private function __construct(string $type, UuidInterface $uuid)
    {
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
