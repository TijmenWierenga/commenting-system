<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Factories;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Article;
use TijmenWierenga\Commenting\User;

function make_user(string $username): User
{
    return new User(Uuid::uuid4(), $username);
}

function make_article(string $title, string $content, UuidInterface $authorId): Article
{
    return new Article(
        Uuid::uuid4(),
        $title,
        $content,
        $authorId
    );
}
