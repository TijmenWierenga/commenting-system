<?php

declare(strict_types=1);

use League\Container\Container;
use Ramsey\Uuid\Uuid;
use TijmenWierenga\Commenting\Models\{CommentableId, User};
use TijmenWierenga\Commenting\Repositories\{ArticleRepository,
    ArticleRepositorySql,
    CommentableRepository,
    CommentableRepositoryProxied,
    CommentRepository,
    CommentRepositorySql,
    UserRepository,
    UserRepositoryInMemory};

/** @var Container $container */

$authorId = Uuid::fromString('186206f9-1ed6-42cf-ab02-3f4d1226a113');
$author = new User($authorId, 'tijmen');

$userRepository = new UserRepositoryInMemory($author);

$container->add(PDO::class)
    ->addArgument($_ENV['MYSQL_DSN'])
    ->addArgument($_ENV['MYSQL_USERNAME'])
    ->addArgument($_ENV['MYSQL_PASSWORD'])
    ->addMethodCall('setAttribute', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])
    ->setShared(true);

$container->add(CommentRepositorySql::class)->addArgument(PDO::class);
$container->add(ArticleRepositorySql::class)->addArgument(PDO::class);
$container->add(ArticleRepository::class, $container->get(ArticleRepositorySql::class));
$container->add(CommentRepository::class, $container->get(CommentRepositorySql::class));
$container->add(UserRepository::class, $userRepository);
$container->add(CommentableRepositoryProxied::class)->addArgument([
    CommentableId::RESOURCE_TYPE_ARTICLE => $container->get(ArticleRepository::class),
    CommentableId::RESOURCE_TYPE_COMMENT => $container->get(CommentRepository::class)
]);
$container->add(CommentableRepository::class, $container->get(CommentableRepositoryProxied::class));
