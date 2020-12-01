<?php

namespace App\Model;

/**
 *
 */
class HomeManager extends AbstractManager
{
    /**
    *
    */
    const TABLE = 'article';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        $articles = $this->pdo->query("SELECT 
            article.id,
            article.name,
            article.price,
            article.description,
            article.quantity,
            color.name as color_name,
            brand.name as brand_name,
            model.name as model_name,
            article.color_id,
            article.brand_id,
            article.model_id
            FROM article 
            INNER JOIN color ON article.color_id=color.id
            INNER JOIN brand ON article.brand_id=brand.id
            INNER JOIN model ON article.model_id=model.id")->fetchAll();

        $result = [];
        foreach ($articles as $article) {
            $statementImg = $this->pdo->prepare('SELECT url FROM image WHERE article_id=:article_id');
            $statementImg->bindValue('article_id', $article['id'], \PDO::PARAM_INT);
            $statementImg->execute();
            $images = $statementImg->fetchAll();
            $article['images'] = $images;
            array_push($result, $article);
        }
        return $result;
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        article.id,
        article.name,
        article.price,
        article.description,
        article.quantity,
        color.name as color_name,
        brand.name as brand_name,
        model.name as model_name,
        article.color_id,
        article.brand_id,
        article.model_id
        FROM article 
        INNER JOIN color ON article.color_id=color.id
        INNER JOIN brand ON article.brand_id=brand.id
        INNER JOIN model ON article.model_id=model.id
        WHERE article.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $article = $statement->fetch();
        $statementImg = $this->pdo->prepare('SELECT url FROM image WHERE article_id=:article_id');
        $statementImg->bindValue('article_id', $id, \PDO::PARAM_INT);
        $statementImg->execute();
        $images = $statementImg->fetchAll();
        $article['images'] = $images;

        return $article;
    }
}
