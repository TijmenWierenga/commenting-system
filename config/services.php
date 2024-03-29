<?php

declare(strict_types=1);

use League\Container\Container;
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Exceptions\ExceptionHandler;
use TijmenWierenga\Commenting\Exceptions\Handlers\{
    CatchAllHandler,
    HttpExceptionHandler,
    NotFoundHandler,
    UnauthenticatedHandler,
    ValidationHandler
};
use TijmenWierenga\Commenting\Hashing\{Argon2IdHasher, Hasher};
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
$container->add(
    CommentableRepositoryProxied::class,
    fn (): CommentableRepositoryProxied => new CommentableRepositoryProxied([
    CommentableId::RESOURCE_TYPE_ARTICLE => $container->get(ArticleRepository::class),
    CommentableId::RESOURCE_TYPE_COMMENT => $container->get(CommentRepository::class)
    ])
);

$container->add(
    ArticleRepository::class,
    fn (): ArticleRepository => $container->get(ArticleRepositorySql::class)
);
$container->add(
    CommentRepository::class,
    fn (): CommentRepository => $container->get(CommentRepositorySql::class)
);
$container->add(
    UserRepository::class,
    fn (): UserRepository => $container->get(UserRepositorySql::class)
);
$container->add(
    CommentableRepository::class,
    fn (): CommentableRepository => $container->get(CommentableRepositoryProxied::class)
);

$container->add(AuthManager::class)
    ->addArgument(UserRepository::class)
    ->addArgument(Hasher::class)
    ->addArgument($_ENV['SECRET_KEY'])
    ->setShared(true);
$container->add(Hasher::class, Argon2IdHasher::class);

// EXCEPTION HANDLER
$container->add(NotFoundHandler::class)->addTag('exception_handlers');
$container->add(UnauthenticatedHandler::class)->addTag('exception_handlers');
$container->add(ValidationHandler::class)->addTag('exception_handlers');
$container->add(HttpExceptionHandler::class)->addTag('exception_handlers');
$container->add(CatchAllHandler::class)->addTag('exception_handlers');
$container->add(ExceptionHandler::class)->addArguments($container->get('exception_handlers'));
$container->add('exception_handler', fn (): ExceptionHandler => $container->get(ExceptionHandler::class));
