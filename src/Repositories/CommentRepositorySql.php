<?php

declare(strict_types=1);

namespace TijmenWierenga\Commenting\Repositories;

use PDO;
use Ramsey\Uuid\UuidInterface;
use TijmenWierenga\Commenting\Exceptions\ModelNotFoundException;
use TijmenWierenga\Commenting\Models\Article;
use TijmenWierenga\Commenting\Models\Comment;

final class CommentRepositorySql implements CommentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(UuidInterface $id): Comment
    {
        $statement = $this->pdo->prepare('SELECT * FROM comments WHERE `uuid` = :id');
        $statement->execute([
            'id' => $id->toString()
        ]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new ModelNotFoundException(Comment::class, $id->toString());
        }

        return Comment::fromScalar($data);
    }

    /**
     * @inheritDoc
     */
    public function findByArticleId(UuidInterface $id): iterable
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM comments 
                WHERE `root_id` = :id AND `root_type` = 'article' 
                ORDER BY `created_at` DESC"
        );
        $statement->execute([
            'id' => $id->toString()
        ]);
        /** @var array $data */
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $item): Comment => Comment::fromScalar($item), $data);
    }

    public function save(Comment $comment): void
    {
        $statement = $this->pdo->prepare('
            INSERT INTO 
            comments (
                `uuid`,
                `author_id`,
                `content`,
                `created_at`,
                `commentable_type`,
                `commentable_id`,
                `root_type`,
                `root_id`
            )
            VALUES (
                :uuid,
                :authorId, 
                :content, 
                :createdAt, 
                :commentableType, 
                :commentableId,
                :rootType,
                :rootId
            )
            ');

        $statement->execute([
            'uuid' => $comment->getId()->toString(),
            'authorId' => $comment->getAuthorId()->toString(),
            'content' => $comment->getContent(),
            'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i'),
            'commentableType' => $comment->getBelongsToId()->getResourceType(),
            'commentableId' => $comment->getBelongsToId()->toString(),
            'rootType' => $comment->getRootId()->getResourceType(),
            'rootId' => $comment->getRootId()->toString()
        ]);
    }
}
