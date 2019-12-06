<?php

declare(strict_types=1);

use League\Route\Router;
use TijmenWierenga\Commenting\Actions\{
    GetAllArticlesAction,
    GetArticleAction,
    GetCommentAction,
    GetCommentsForArticleAction,
    GetUserAction,
    LoginAction,
    RegisterUserAction,
    SaveCommentAction
};
use TijmenWierenga\Commenting\Middleware\AuthenticationMiddleware;

/** @var Router $router */

$router->post(
    '/user',
    RegisterUserAction::class
);

$router->get(
    '/user/{id}',
    GetUserAction::class
);

$router->post(
    '/login',
    LoginAction::class
);

$router->get(
    '/article',
    GetAllArticlesAction::class
);

$router->get(
    '/article/{id}',
    GetArticleAction::class
);

$router->get(
    '/article/{id}/comments',
    GetCommentsForArticleAction::class
);

$router->post(
    '/comment',
    SaveCommentAction::class
)->middleware($container->get(AuthenticationMiddleware::class));

$router->get(
    '/comment/{id}',
    GetCommentAction::class
);
