<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Comment;
use TijmenWierenga\Commenting\Commentable;
use function TijmenWierenga\Tests\Commenting\Factories\make_article;
use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class CommentTest extends TestCase
{
    public function testItCreatesACommentForAnArticle(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $comment = Comment::newFor($article, $user->getId(), 'Great article mate');

        static::assertEquals($article, $comment->belongsTo());
    }

    public function testItCreatesACommentForAnotherComment(): void
    {
        $user = make_user('Tijmen');
        $article = make_article('Great testing article', 'Testing is awesome', $user->getId());

        $articleComment = Comment::newFor($article, $user->getId(), 'Great article mate');
        $commentOnComment = Comment::newFor($articleComment, $user->getId(), 'Great article mate');

        static::assertEquals($article, $articleComment->belongsTo());
        static::assertEquals($articleComment, $commentOnComment->belongsTo());
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
        };
        $user = make_user('tijmen');

        Comment::newFor($nonCommentable, $user->getId(), 'This is bullshit!');
    }
}
