<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Models;

use Ramsey\Uuid\UuidInterface;

final class Article implements Commentable
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

    public function resourceType(): string
    {
        return 'article';
    }

    public function getId(): CommentableId
    {
        return $this->id;
    }

    public function getRootId(): CommentableId
    {
        return $this->getId();
    }
}
