<?php

declare(strict_types=1);

use League\Container\Container;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Models\{Article, Comment, CommentableId, User};
use TijmenWierenga\Commenting\Repositories\{
    ArticleRepository,
    ArticleRepositoryInMemory,
    CommentableRepository,
    CommentableRepositoryInMemory,
    CommentRepository,
    CommentRepositoryInMemory,
    CommentRepositorySql,
    UserRepository,
    UserRepositoryInMemory};

/** @var Container $container */

$authorId = Uuid::fromString('186206f9-1ed6-42cf-ab02-3f4d1226a113');
$author = new User($authorId, 'tijmen');
$article = new Article(
    CommentableId::fromScalar('article', '780fdc7e-adeb-4cf5-9521-e53c52557a6d'),
    'My first article',
    'This is some content',
    $author->getId()
);
$comments = [
    $comment = Comment::newFor($article, $authorId, 'I like it'),
    Comment::newFor($article, $authorId, 'Awesome'),
    Comment::newFor($article, $authorId, 'This sucks'),
    Comment::newFor($comment, $authorId, 'I like that you like it!')
];
$commentRepository = new CommentRepositoryInMemory(...$comments);
$articleRepository = new ArticleRepositoryInMemory($article);
$commentableRepository = new CommentableRepositoryInMemory($article, ...$comments);
$userRepository = new UserRepositoryInMemory($author);

$container->add(PDO::class)
    ->addArgument($_ENV['MYSQL_DSN'])
    ->addArgument($_ENV['MYSQL_USERNAME'])
    ->addArgument($_ENV['MYSQL_PASSWORD'])
    ->addMethodCall('setAttribute', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])
    ->setShared(true);

$container->add(CommentRepositorySql::class)->addArgument(PDO::class);
$container->add(ArticleRepository::class, $articleRepository);
$container->add(CommentableRepository::class, $commentableRepository);
$container->add(CommentRepository::class, $container->get(CommentRepositorySql::class));
$container->add(UserRepository::class, $userRepository);
