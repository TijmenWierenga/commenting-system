<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use League\Container\Container;
use League\Route\Strategy\{ApplicationStrategy, StrategyInterface};
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Psr7\ServerRequest;
use League\Route\Router;
use TijmenWierenga\Commenting\Actions\{GetCommentsForArticleAction, SaveCommentAction};
use TijmenWierenga\Commenting\Models\{Article, Comment, User};
use TijmenWierenga\Commenting\Repositories\{
    ArticleRepository,
    ArticleRepositoryInMemory,
    CommentableRepository,
    CommentableRepositoryInMemory,
    CommentRepository,
    CommentRepositoryInMemory,
    UserRepository,
    UserRepositoryInMemory
};
use function Http\Response\send;

$authorId = Uuid::fromString('186206f9-1ed6-42cf-ab02-3f4d1226a113');
$author = new User($authorId, 'tijmen');
$articleId = Uuid::fromString('780fdc7e-adeb-4cf5-9521-e53c52557a6d');
$article = new Article($articleId, 'My first article', 'This is some content', $author->getId());
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

$request = ServerRequest::fromGlobals();

$container = new Container();

$container->add(ArticleRepository::class, $articleRepository);
$container->add(CommentableRepository::class, $commentableRepository);
$container->add(CommentRepository::class, $commentRepository);
$container->add(UserRepository::class, $userRepository);

$container->delegate(
    new League\Container\ReflectionContainer
);
/** @var StrategyInterface $strategy */
$strategy = (new ApplicationStrategy())->setContainer($container);
/** @var Router $router */
$router = (new Router())->setStrategy($strategy);

$router->get(
    '/article/{id}/comments',
    GetCommentsForArticleAction::class
);
$router->post(
    '/comment',
    SaveCommentAction::class
);

$response = $router->dispatch($request);

send($response);
