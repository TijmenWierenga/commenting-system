<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Services;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Models\CommentableId;
use TijmenWierenga\Commenting\Repositories\{
    CommentableRepositoryInMemory,
    CommentRepositoryInMemory,
    UserRepositoryInMemory
};
use TijmenWierenga\Commenting\Services\SaveCommentService;

use function TijmenWierenga\Tests\Commenting\Factories\{make_article, make_user};

class SaveCommentServiceTest extends TestCase
{
    public function testItCannotSaveACommentIfTheAuthorDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $author = make_user('tijmen');
        $article = make_article('PHP is awesome', 'It is!', $author->getId());
        $commentableRepository = new CommentableRepositoryInMemory($article);
        $commentRepository = new CommentRepositoryInMemory();
        $userRepository = new UserRepositoryInMemory(); // Author does not exist here
        $action = new SaveCommentService($commentableRepository, $commentRepository, $userRepository);

        $action(
            CommentableId::RESOURCE_TYPE_ARTICLE,
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
        $action = new SaveCommentService($commentableRepository, $commentRepository, $userRepository);

        $action(
            CommentableId::RESOURCE_TYPE_ARTICLE,
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
        $action = new SaveCommentService($commentableRepository, $commentRepository, $userRepository);

        $comment = $action(
            CommentableId::RESOURCE_TYPE_ARTICLE,
            $article->getId()->toString(),
            $author->getId()->toString(),
            'I really like this article'
        );

        static::assertEquals($comment, $commentRepository->find($comment->getId()->getUuid()));
    }
}
