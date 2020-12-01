<?php

namespace App\Model;

/**
 *
 */
class CarousselManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'caroussel';
    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $caroussel
     * @return int
     */
    public function insert(array $caroussel): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`name`,`url`) 
            VALUES 
            (:name, :url)"
        );
        $statement->bindValue('name', $caroussel['name'], \PDO::PARAM_STR);
        $statement->bindValue('url', $caroussel['url'], \PDO::PARAM_STR);
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
            $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE caroussel.id=:id");
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
    }

    /**
     * @param array $caroussel
     * @return bool
     */
    public function update(array $caroussel): bool
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET 
            `name` = :name,
            `url` = :url
            WHERE caroussel.id=:id"
        );
        $statement->bindValue('id', $caroussel['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $caroussel['name'], \PDO::PARAM_STR);
        $statement->bindValue('url', $caroussel['url'], \PDO::PARAM_STR);
        return $statement->execute();
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT 
            caroussel.id,
            caroussel.name,
            caroussel.url
            FROM caroussel ")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        caroussel.id,
        caroussel.name,
        caroussel.url
        FROM caroussel 
        WHERE caroussel.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
