<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use Ramsey\Uuid\UuidInterface;

interface Commentable
{
    public function resourceType(): string;
    public function getId(): UuidInterface;
    public function getRoot(): Commentable;
}
