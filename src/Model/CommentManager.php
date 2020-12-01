<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class CommentManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'comment';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT 
            comment.id,
            comment.review,
            article.name as article_name,
            user.firstname as user_firstname,
            comment.article_id,
            comment.user_id
            FROM comment 
            INNER JOIN article ON comment.article_id=article.id
            INNER JOIN user ON comment.user_id=user.id")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        comment.id,
        comment.review,
        article.name as article_name,
        user.firstname as user_firstname,
        comment.article_id,
        comment.user_id
        FROM comment 
        INNER JOIN article ON comment.article_id=article.id
        INNER JOIN user ON comment.user_id=user.id
        WHERE comment.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectAllById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT
        comment.id,
        comment.review,
        article.name as article_name,
        user.firstname as user_firstname,
        user.lastname as user_lastname,
        comment.article_id,
        comment.user_id
        FROM comment 
        INNER JOIN article ON comment.article_id=article.id
        INNER JOIN user ON comment.user_id=user.id
        WHERE comment.article_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }


    /**
     * @param array $article
     * @return int
     */
    public function insert(array $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`article_id`,`user_id`,`review`) 
            VALUES 
            (:article_id, :user_id, :review)"
        );
        $statement->bindValue('article_id', $article['article_id'], \PDO::PARAM_INT);
        $statement->bindValue('user_id', $article['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('review', $article['review'], \PDO::PARAM_STR);
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
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }




    public function update(array $article)
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `id` = :id,
            `article_id` = :article_id,
            `user_id` = :user_id,
            `review` = :review
            WHERE id=:id"
        );
        $statement->bindValue('id', $article['id'], \PDO::PARAM_INT);
        $statement->bindValue('article_id', $article['article_id'], \PDO::PARAM_INT);
        $statement->bindValue('user_id', $article['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('review', $article['review'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
