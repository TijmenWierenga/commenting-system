<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Actions;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Actions\GetCommentsForArticleAction;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Repositories\ArticleRepositoryInMemory;
use TijmenWierenga\Commenting\Repositories\CommentRepositoryInMemory;
use function TijmenWierenga\Tests\Commenting\Factories\make_article;
use function TijmenWierenga\Tests\Commenting\Factories\make_user;

class GetCommentsForArticleActionTest extends TestCase
{
    public function testItReturnsANonFoundExceptionWhenArticleDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $author = make_user('tijmen');
        $article = make_article('PHP is great!', 'So opiniated!', $author->getId());
        $commentRepository = new CommentRepositoryInMemory();
        $articleRepository = new ArticleRepositoryInMemory(); // No articles in repository
        $action = new GetCommentsForArticleAction($commentRepository, $articleRepository);

        $action($article->getId());
    }

    public function testItReturnsAllCommentsForAnArticle(): void
    {
        $author = make_user('tijmen');
        $firstArticle = make_article('This test is good', 'Yes man!', $author->getId());
        $secondArticle = make_article('Testing', 'Just do it', $author->getId());

        $firstArticleComments = [
            Comment::newFor($firstArticle, $author->getId(), 'First'),
            Comment::newFor($firstArticle, $author->getId(), 'Second'),
            Comment::newFor($firstArticle, $author->getId(), 'Third'),
        ];
        $secondArticleComments = [
            Comment::newFor($secondArticle, $author->getId(), 'First'),
            Comment::newFor($secondArticle, $author->getId(), 'Second'),
            Comment::newFor($secondArticle, $author->getId(), 'Third'),
        ];

        $commentRepository = new CommentRepositoryInMemory(...[...$firstArticleComments, ...$secondArticleComments]);
        $articleRepository = new ArticleRepositoryInMemory($firstArticle, $secondArticle);

        $action = new GetCommentsForArticleAction($commentRepository, $articleRepository);
        $comments = $action($firstArticle->getId());

        static::assertEquals($firstArticleComments, $comments);
    }
}
