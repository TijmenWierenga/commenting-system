<?php

declare(strict_types=1);

use League\Container\Container;
use TijmenWierenga\Commenting\Models\{CommentableId};
use TijmenWierenga\Commenting\Repositories\{
    ArticleRepository,
    ArticleRepositorySql,
    CommentableRepository,
    CommentableRepositoryProxied,
    CommentRepository,
    CommentRepositorySql,
    UserRepository,
    UserRepositorySql
};
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Hashing\{Argon2IdHasher, Hasher};
use TijmenWierenga\Commenting\Exceptions\ExceptionHandler;
use TijmenWierenga\Commenting\Exceptions\Handlers\CatchAllHandler;
use TijmenWierenga\Commenting\Exceptions\Handlers\NotFoundHandler;
use TijmenWierenga\Commenting\Middleware\AuthenticationMiddleware;

/** @var Container $container */

// SERVICE DEFINITIONS
$container->add(PDO::class)
    ->addArgument($_ENV['MYSQL_DSN'])
    ->addArgument($_ENV['MYSQL_USERNAME'])
    ->addArgument($_ENV['MYSQL_PASSWORD'])
    ->addMethodCall('setAttribute', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])
    ->setShared(true);

$container->add(CommentRepositorySql::class)->addArgument(PDO::class);
$container->add(ArticleRepositorySql::class)->addArgument(PDO::class);
$container->add(UserRepositorySql::class)->addArgument(PDO::class);
$container->add(ArticleRepository::class, $container->get(ArticleRepositorySql::class));
$container->add(CommentRepository::class, $container->get(CommentRepositorySql::class));
$container->add(UserRepository::class, $container->get(UserRepositorySql::class));

$container->add(CommentableRepositoryProxied::class)->addArgument([
    CommentableId::RESOURCE_TYPE_ARTICLE => $container->get(ArticleRepository::class),
    CommentableId::RESOURCE_TYPE_COMMENT => $container->get(CommentRepository::class)
]);
$container->add(CommentableRepository::class, $container->get(CommentableRepositoryProxied::class));
$container->add(AuthManager::class)
    ->addArgument(UserRepository::class)
    ->addArgument(Hasher::class)
    ->addArgument($_ENV['SECRET_KEY'])
    ->setShared(true);
$container->add(Hasher::class, Argon2IdHasher::class);

// EXCEPTION HANDLER
$container->add(NotFoundHandler::class)->addTag('exception_handlers');
$container->add(CatchAllHandler::class)->addTag('exception_handlers');
$container->add(ExceptionHandler::class)->addArguments($container->get('exception_handlers'));
$container->add('exception_handler', fn (): ExceptionHandler => $container->get(ExceptionHandler::class));
