<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Transformers;

use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Models\Comment;
use TijmenWierenga\Commenting\Transformers\Comments\TreeBuilder;

use function TijmenWierenga\Tests\Commenting\Factories\make_article;
use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class TreeBuilderTest extends TestCase
{
    public function testItBuildsATreeForACommentableWithoutComments(): void
    {
        $user = make_user('tijmen');
        $article = make_article('PHP is awesome', 'What a content', $user->getId());
        $comment = Comment::newFor($article, $user->getId(), 'Such comment');

        $treeBuilder = new TreeBuilder();
        $result = $treeBuilder->transform($comment);

        $expected = [
            [
                'uuid' => $comment->getId()->toString(),
                'authorId' => $user->getId()->toString(),
                'content' => 'Such comment',
                'createdAt' => $comment->getCreatedAt()->format(DATE_ATOM),
                'comments' => []
            ]
        ];

        static::assertEquals($expected, $result);
    }

    public function testItBuildsARecursiveTree(): void
    {
        $user = make_user('tijmen');
        $article = make_article('PHP is awesome', 'What a content', $user->getId());
        $comment = Comment::newFor($article, $user->getId(), 'Such comment');
        $commentOnComment = Comment::newFor($comment, $user->getId(), 'I commented on your comment');

        $treeBuilder = new TreeBuilder();
        $result = $treeBuilder->transform($comment, $commentOnComment);

        $expected = [
            [
                'uuid' => $comment->getId()->toString(),
                'authorId' => $user->getId()->toString(),
                'content' => 'Such comment',
                'createdAt' => $comment->getCreatedAt()->format(DATE_ATOM),
                'comments' => [
                    [
                        'uuid' => $commentOnComment->getId()->toString(),
                        'authorId' => $user->getId()->toString(),
                        'content' => 'I commented on your comment',
                        'createdAt' => $commentOnComment->getCreatedAt()->format(DATE_ATOM),
                        'comments' => []
                    ]
                ]
            ]
        ];

        static::assertEquals($expected, $result);
    }
}
