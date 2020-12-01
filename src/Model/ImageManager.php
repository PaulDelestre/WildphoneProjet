<?php

namespace App\Model;

/**
 *
 */
class ImageManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'image';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $image
     * @return int
     */
    public function insert(array $image): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`url`,`article_id`) 
            VALUES 
            (:url, :article_id)"
        );
        $statement->bindValue('url', $image['url'], \PDO::PARAM_STR);
        $statement->bindValue('article_id', $image['article_id'], \PDO::PARAM_INT);
        if ($statement->execute()) {
            return (int) $this->pdo->lastInsertId();
        }
    }
    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
            $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE image.id=:id");
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
    }

    /**
     * @param array $image
     * @return bool
     */
    public function update(array $image): bool
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET 
            `id` = :id,
            `url` = :url,
            `article_id` = :article_id
            WHERE image.id=:id"
        );
        $statement->bindValue('id', $image['id'], \PDO::PARAM_INT);
        $statement->bindValue('url', $image['url'], \PDO::PARAM_STR);
        $statement->bindValue('article_id', $image['article_id'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT 
            image.id,
            image.url,
            article.id as article_id,
            article.name as article_name,
            color.name as color_article
            FROM image 
            INNER JOIN article ON image.article_id=article.id
            INNER JOIN color ON article.color_id=color.id")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        image.id,
        image.url,
        article.id as article_id
        FROM image 
        INNER JOIN article ON image.article_id=article.id
        WHERE image.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
