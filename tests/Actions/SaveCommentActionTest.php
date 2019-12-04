<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Commenting\Actions;

use GuzzleHttp\Psr7\ServerRequest;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Commenting\Actions\SaveCommentAction;
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Hashing\PlainTextHasher;
use TijmenWierenga\Commenting\Repositories\CommentableRepositoryInMemory;
use TijmenWierenga\Commenting\Repositories\CommentRepositoryInMemory;
use TijmenWierenga\Commenting\Repositories\UserRepositoryInMemory;
use TijmenWierenga\Commenting\Services\SaveCommentService;

use function TijmenWierenga\Tests\Commenting\Factories\make_article;
use function TijmenWierenga\Tests\Commenting\Factories\make_user;

final class SaveCommentActionTest extends TestCase
{
    public function testItSavesACommentWithTheAuthenticatedUserAsAuthor(): void
    {
        $author = make_user('tijmen');
        $article = make_article('PHP is great', 'This test too', $author->getId());

        $commentableRepository = new CommentableRepositoryInMemory($article);
        $commentRepository = new CommentRepositoryInMemory();
        $userRepository = new UserRepositoryInMemory($author);

        $service = new SaveCommentService($commentableRepository, $commentRepository, $userRepository);
        $authManager = new AuthManager($userRepository, new PlainTextHasher(), 'secret-key');
        $accessToken = $authManager->login('tijmen', '123456'); // default password

        $action = new SaveCommentAction($service, $authManager);

        $request = new ServerRequest(
            'POST',
            '/comment',
            [
                'Content-Type' => 'application/json',
                'Authorization' => (string) $accessToken
            ],
            json_encode([
                'content' => 'I love this article',
                'resource' => [
                    'type' => 'article',
                    'id' => $article->getId()->toString()
                ]
            ], JSON_THROW_ON_ERROR, 512)
        );

        $authManager->authenticate($request);

        $response = $action($request);
        $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        static::assertEquals('I love this article', $data['content']);
        static::assertEquals($author->getId()->toString(), $data['authorId']);
    }
}
