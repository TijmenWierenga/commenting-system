<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use TijmenWierenga\Commenting\Authentication\AuthManager;
use TijmenWierenga\Commenting\Models\User;
use TijmenWierenga\Commenting\Services\SaveCommentService;

class SaveCommentAction
{
    private SaveCommentService $saveCommentService;
    private AuthManager $authManager;

    public function __construct(SaveCommentService $saveCommentService, AuthManager $authManager)
    {
        $this->saveCommentService = $saveCommentService;
        $this->authManager = $authManager;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $resourceType = $body['resource']['type'];
        $resourceId = $body['resource']['id'];
        $content = $body['content'];

        /** @var User $user */
        $user = $this->authManager->getAuthenticatedUser();

        $comment = ($this->saveCommentService)($resourceType, $resourceId, $user->getId()->toString(), $content);

        return new Response(
            201,
            ['Content-Type' => 'application/json'],
            json_encode($comment, JSON_THROW_ON_ERROR, 512)
        );
    }
}
