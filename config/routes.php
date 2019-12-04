<?php

declare(strict_types=1);

use League\Route\Router;
use TijmenWierenga\Commenting\Actions\{GetArticleAction, GetCommentsForArticleAction, LoginAction, SaveCommentAction};
use TijmenWierenga\Commenting\Middleware\AuthenticationMiddleware;

/** @var Router $router */

$router->post(
    '/login',
    LoginAction::class
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
