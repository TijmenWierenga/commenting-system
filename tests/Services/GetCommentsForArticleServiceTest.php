<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Services;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Repositories\{ArticleRepositoryInMemory, CommentRepositoryInMemory};
use TijmenWierenga\Commenting\Services\GetCommentsForArticleService;
use function TijmenWierenga\Tests\Commenting\Factories\{make_article, make_user};

class GetCommentsForArticleServiceTest extends TestCase
{
    public function testItReturnsANonFoundExceptionWhenArticleDoesNotExist(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $author = make_user('tijmen');
        $article = make_article('PHP is great!', 'So opiniated!', $author->getId());
        $commentRepository = new CommentRepositoryInMemory();
        $articleRepository = new ArticleRepositoryInMemory(); // No articles in repository
        $service = new GetCommentsForArticleService($commentRepository, $articleRepository);

        $service($article->getId());
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

        $service = new GetCommentsForArticleService($commentRepository, $articleRepository);
        $comments = $service($firstArticle->getId());

        static::assertEquals($firstArticleComments, $comments);
    }
}
