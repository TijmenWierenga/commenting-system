<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting;

use Ramsey\Uuid\UuidInterface;

final class Article
{
    private UuidInterface $id;
    private string $title;
    private string $content;

    private function __construct(UuidInterface $id, string $title, string $content)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }
}
