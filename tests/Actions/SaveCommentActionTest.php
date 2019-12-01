<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Actions;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Actions\SaveCommentAction;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Repositories\CommentableRepositoryInMemory;
use TijmenWierenga\Commenting\Repositories\CommentRepositoryInMemory;
use TijmenWierenga\Commenting\Repositories\UserRepositoryInMemory;
use function TijmenWierenga\Tests\Commenting\Factories\make_article;
use function TijmenWierenga\Tests\Commenting\Factories\make_user;

class SaveCommentActionTest extends TestCase
{
    public function testItCannotSaveACommentIfTheAuthorDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $author = make_user('tijmen');
        $article = make_article('PHP is awesome', 'It is!', $author->getId());
        $commentableRepository = new CommentableRepositoryInMemory($article);
        $commentRepository = new CommentRepositoryInMemory();
        $userRepository = new UserRepositoryInMemory(); // Author does not exist here
        $action = new SaveCommentAction($commentableRepository, $commentRepository, $userRepository);

        $action(
            Comment::RESOURCE_TYPE_ARTICLE,
            $article->getId()->toString(),
            $author->getId()->toString(),
            'I really like this article'
        );
    }

    public function testItCannotSaveACommentIfTheRootCommentableDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $author = make_user('tijmen');
        $article = make_article('PHP is awesome', 'It is!', $author->getId());
        $commentableRepository = new CommentableRepositoryInMemory(); // Article does not exist here
        $commentRepository = new CommentRepositoryInMemory();
        $userRepository = new UserRepositoryInMemory($author);
        $action = new SaveCommentAction($commentableRepository, $commentRepository, $userRepository);

        $action(
            Comment::RESOURCE_TYPE_ARTICLE,
            $article->getId()->toString(),
            $author->getId()->toString(),
            'I really like this article'
        );
    }

    public function testItSavesANewComment(): void
    {
        $author = make_user('tijmen');
        $article = make_article('PHP is awesome', 'It is!', $author->getId());
        $commentableRepository = new CommentableRepositoryInMemory($article);
        $commentRepository = new CommentRepositoryInMemory();
        $userRepository = new UserRepositoryInMemory($author);
        $action = new SaveCommentAction($commentableRepository, $commentRepository, $userRepository);

        $comment = $action(
            Comment::RESOURCE_TYPE_ARTICLE,
            $article->getId()->toString(),
            $author->getId()->toString(),
            'I really like this article'
        );

        static::assertEquals($comment, $commentRepository->find($comment->getId()));
    }
}
