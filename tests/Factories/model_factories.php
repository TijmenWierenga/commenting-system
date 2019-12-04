<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Factories;

use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\{Article, CommentableId, User};

function make_user(string $username): User
{
    return User::new($username, '123456');
}

function make_article(string $title, string $content, UuidInterface $authorId): Article
{
    return new Article(
        CommentableId::new('article'),
        $title,
        $content,
        $authorId
    );
}
