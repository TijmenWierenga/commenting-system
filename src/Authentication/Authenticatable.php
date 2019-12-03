<?php
declare(strict_types=1);

namespace TijmenWierenga\Commenting\Authentication;

use Ramsey\Uuid\UuidInterface;

interface Authenticatable
{
    public function getApiToken(): string;
    public function getId(): UuidInterface;
}
