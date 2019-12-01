<?php

declare(strict_types=1);

use League\Route\Router;
use TijmenWierenga\Commenting\Actions\{GetCommentsForArticleAction, SaveCommentAction};

/** @var Router $router */

$router->get(
    '/article/{id}/comments',
    GetCommentsForArticleAction::class
);
$router->post(
    '/comment',
    SaveCommentAction::class
);
