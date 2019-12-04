<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;

final class Article implements Commentable, JsonSerializable
{
    private CommentableId $id;
    private string $title;
    private string $content;
    private UuidInterface $authorId;

    public function __construct(CommentableId $id, string $title, string $content, UuidInterface $authorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
    }

    public function getId(): CommentableId
    {
        return $this->id;
    }

    public function getRootId(): CommentableId
    {
        return $this->getId();
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->getId()->toString(),
            'authorId' => $this->authorId->toString(),
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
