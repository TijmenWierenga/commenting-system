<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Models;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\{Uuid, UuidInterface};
use TijmenWierenga\Commenting\Models\{Comment, Commentable, CommentableId};

use function TijmenWierenga\Tests\Commenting\Factories\{make_article, make_user};

final class CommentTest extends TestCase
{
    public function testItCreatesACommentForAnArticle(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $comment = Comment::newFor($article, $user->getId(), 'Great article mate');

        static::assertEquals(
            $article->getId()->toString(),
            $comment->getRootId()->getUuid()->toString()
        );
        static::assertEquals(
            $article->getId()->getResourceType(),
            $comment->getRootId()->getResourceType()
        );
        static::assertEquals(
            $article->getId()->toString(),
            $comment->belongsToId()->getUuid()->toString()
        );
        static::assertEquals(
            $article->getId()->getResourceType(),
            $comment->getRootId()->getResourceType()
        );
    }

    public function testItCreatesACommentForAnotherComment(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $articleComment = Comment::newFor($article, $user->getId(), 'Great article mate');
        $commentOnComment = Comment::newFor($articleComment, $user->getId(), 'Great article mate');

        static::assertEquals(
            $article->getId()->toString(),
            $articleComment->belongsToId()->getUuid()->toString()
        );
        static::assertEquals(
            $articleComment->getId()->toString(),
            $commentOnComment->belongsToId()->getUuid()->toString()
        );
        static::assertEquals(
            $article->getId()->toString(),
            $commentOnComment->getRootId()->getUuid()->toString()
        );
    }

    public function testItCanOnlyCreateACommentForASupportedCommentableEntity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid resource type provided for comment (invalid-resource). Available types: article, comment'
        );

        $nonCommentable = new class implements Commentable {

            public function getId(): CommentableId
            {
                return CommentableId::new('invalid-resource');
            }

            public function getRootId(): CommentableId
            {
                return $this->getId();
            }
        };
        $user = make_user('tijmen');

        Comment::newFor($nonCommentable, $user->getId(), 'This is bullshit!');
    }
}
