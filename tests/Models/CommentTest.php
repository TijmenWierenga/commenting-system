<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Models;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\{Uuid, UuidInterface};
use TijmenWierenga\Commenting\Models\{Comment, Commentable};
use function TijmenWierenga\Tests\Commenting\Factories\{make_article, make_user};

final class CommentTest extends TestCase
{
    public function testItCreatesACommentForAnArticle(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $comment = Comment::newFor($article, $user->getId(), 'Great article mate');

        static::assertEquals($article, $comment->belongsTo());
        static::assertEquals($article, $comment->getRoot());
    }

    public function testItCreatesACommentForAnotherComment(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $articleComment = Comment::newFor($article, $user->getId(), 'Great article mate');
        $commentOnComment = Comment::newFor($articleComment, $user->getId(), 'Great article mate');

        static::assertEquals($article, $articleComment->belongsTo());
        static::assertEquals($articleComment, $commentOnComment->belongsTo());
        static::assertEquals($article, $commentOnComment->getRoot());
    }

    public function testItCanOnlyCreateACommentForASupportedCommentableEntity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid resource type provided for comment (invalid-resource). Available types: article, comment'
        );

        $nonCommentable = new class implements Commentable {
            public function resourceType(): string
            {
                return 'invalid-resource';
            }

            public function getId(): UuidInterface
            {
                return Uuid::uuid4();
            }

            public function getRoot(): Commentable
            {
                return $this;
            }
        };
        $user = make_user('tijmen');

        Comment::newFor($nonCommentable, $user->getId(), 'This is bullshit!');
    }
}
