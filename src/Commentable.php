<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

interface Commentable
{
    public function resourceType(): string;
    public function getId(): UuidInterface;
}
