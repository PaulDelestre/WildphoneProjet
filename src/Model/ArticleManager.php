<?php

namespace App\Model;

/**
 *
 */
class ArticleManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'article';

    /**
     * constant for selectAllWithImage
     */
    const SELECTALL = "SELECT 
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
    INNER JOIN model ON article.model_id=model.id";

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $article
     * @return int
     */
    public function insert(array $article): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
            " (`name`,`price`,`description`,`quantity`,`color_id`,`brand_id`,`model_id`)
            VALUES 
            (:name, :price, :description, :quantity, :color_id, :brand_id, :model_id)"
        );
        $statement->bindValue('name', $article['name'], \PDO::PARAM_STR);
        $statement->bindValue('price', $article['price'], \PDO::PARAM_INT);
        $statement->bindValue('description', $article['description'], \PDO::PARAM_STR);
        $statement->bindValue('quantity', $article['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('color_id', $article['color_id'], \PDO::PARAM_INT);
        $statement->bindValue('brand_id', $article['brand_id'], \PDO::PARAM_INT);
        $statement->bindValue('model_id', $article['model_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int) $this->pdo->lastInsertId();
        }
    }

     // STOCK
    public function updateQty($idArticle, $qty)
    {
        $statement = $this->pdo->prepare("SELECT quantity FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $idArticle, \PDO::PARAM_INT);
        $statement->execute();
        $oldQty = $statement->fetch();
        $newQty = $oldQty['quantity'] - $qty;
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET quantity=:qty WHERE id=:id");
        $statement->bindValue('id', $idArticle, \PDO::PARAM_INT);
        $statement->bindValue('qty', $newQty, \PDO::PARAM_INT);
        $statement->execute();
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


    /**
     * @param array $article
     * @return bool
     */
    public function update(array $article): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `id` = :id,
            `name` = :name,
            `price` = :price,
            `description` = :description,
            `quantity` = :quantity,
            `color_id` = :color_id,
            `brand_id` = :brand_id,
            `model_id` = :model_id
            WHERE id=:id"
        );
        $statement->bindValue('id', $article['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $article['name'], \PDO::PARAM_STR);
        $statement->bindValue('price', $article['price'], \PDO::PARAM_INT);
        $statement->bindValue('description', $article['description'], \PDO::PARAM_STR);
        $statement->bindValue('color_id', $article['color_id'], \PDO::PARAM_INT);
        $statement->bindValue('brand_id', $article['brand_id'], \PDO::PARAM_INT);
        $statement->bindValue('model_id', $article['model_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $article['quantity'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE article.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $article= $statement->fetch();
        
        $statement2 = $this->pdo->prepare("SELECT id, url FROM image WHERE article_id=:article_id");
        $statement2->bindValue('article_id', $id, \PDO::PARAM_INT);
        $statement2->execute();
        $images = $statement2->fetchAll();
        $article['images'] = $images;
        $result = $article;

        return $result;
    }

    public function selectAllWithImage() :array
    {
        $statement = $this->pdo->query(self::SELECTALL);
        
        $articles = $statement->fetchAll();

        return $this->getAllImage($articles);
    }

    public function selectByFilter($filter) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE ".$filter."_id =:".$filter."_id");
        $statement->bindValue($filter."_id", $_POST[$filter], \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    public function selectAllWithTwoFilter($filter1, $filter2) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE ". $filter1 ."_id =:".$filter1.
        "_id AND ". $filter2 ."_id =:".$filter2."_id");
        $statement->bindValue($filter1."_id", $_POST[$filter1], \PDO::PARAM_STR);
        $statement->bindValue($filter2."_id", $_POST[$filter2], \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    public function selectAllWithThreeFilter($filter1, $filter2, $filter3) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE ". $filter1 ."_id =:".$filter1.
        "_id AND ". $filter2 ."_id =:".$filter2.
        "_id AND ".$filter3 ."_id =:".$filter3."_id");
        $statement->bindValue($filter1."_id", $_POST[$filter1], \PDO::PARAM_STR);
        $statement->bindValue($filter2."_id", $_POST[$filter2], \PDO::PARAM_STR);
        $statement->bindValue($filter3."_id", $_POST[$filter3], \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    public function search($search) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE article.name LIKE :search ");
        $statement->bindValue('search', '%'.$search.'%', \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    public function searchByColor($search) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE color.name LIKE :search ");
        $statement->bindValue('search', '%'.$search.'%', \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    public function searchByBrand($search) :array
    {

        $statement = $this->pdo->prepare(self::SELECTALL .
        " WHERE brand.name LIKE :search ");
        $statement->bindValue('search', '%'.$search.'%', \PDO::PARAM_STR);
        $statement->execute();
        $articles = $statement->fetchAll();
        return $this->getAllImage($articles);
    }

    private function getAllImage(array $articles)
    {
        $result = [];
        foreach ($articles as $article) {
            $statement = $this->pdo->prepare("SELECT id, url FROM image WHERE article_id=:article_id");
            $statement->bindValue('article_id', $article['id'], \PDO::PARAM_INT);
            $statement->execute();
            $images = $statement->fetchAll();
            $article['images'] = $images;
            $result[] = $article;
        }
        return $result;
    }
}
