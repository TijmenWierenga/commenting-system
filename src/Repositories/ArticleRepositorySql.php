<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Models\Article;
use TijmenWierenga\Commenting\Models\CommentableId;

final class ArticleRepositorySql implements ArticleRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(UuidInterface $id): Article
    {
        $statement = $this->pdo->prepare('SELECT * FROM `articles` WHERE `uuid` = :uuid');
        $statement->execute([
            'uuid' => $id->toString()
        ]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return new Article(
            CommentableId::fromScalar(CommentableId::RESOURCE_TYPE_ARTICLE, $data['uuid']),
            $data['title'],
            $data['content'],
            Uuid::fromString($data['author_id'])
        );
    }

    public function exists(UuidInterface $id): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM `articles` WHERE `uuid` = :uuid');
        $statement->execute([
            'uuid' => $id->toString()
        ]);
        $result = $statement->fetchColumn();

        return (bool) $result;
    }
}
