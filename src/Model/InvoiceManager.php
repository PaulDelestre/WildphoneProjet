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
class InvoiceManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'invoice';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $invoice
     * @return int
     */
    public function insert(array $invoice): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (`quantity`, `total`, `article_id`,`purchase_id`)
        VALUES
        (:quantity, :total, :article_id, :purchase_id)");
        $statement->bindValue('quantity', $invoice['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('total', $invoice['total'], \PDO::PARAM_INT);
        $statement->bindValue('article_id', $invoice['article_id'], \PDO::PARAM_INT);
        $statement->bindValue('purchase_id', $invoice['purchase_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
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


    /**
     * @param array $invoice
     * @return bool
     */

    public function update(array $invoice): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `id` = :id,
            `article_id` = :article_id,
            `purchase_id` = :purchase_id,
            `quantity` = :quantity,
            `total` = :total
            WHERE id=:id"
        );
        $statement->bindValue('id', $invoice['id'], \PDO::PARAM_INT);
        $statement->bindValue('article_id', $invoice['article_id'], \PDO::PARAM_INT);
        $statement->bindValue('purchase_id', $invoice['purchase_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $invoice['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('total', $invoice['total'], \PDO::PARAM_INT);
        
        return $statement->execute();
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT 
            invoice.id,
            invoice.quantity,
            invoice.total,
            article.name as article_name,
            purchase.id as purchase_id,
            invoice.article_id,
            invoice.purchase_id
            FROM invoice 
            INNER JOIN article ON invoice.article_id=article.id
            INNER JOIN purchase ON invoice.purchase_id=purchase.id")->fetchAll();
    }

    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
            invoice.id,
            invoice.purchase_id,
            invoice.article_id as article_id,
            article.name as article_name,
            invoice.quantity,
            invoice.total
            FROM invoice 
            INNER JOIN article ON invoice.article_id=article.id
            WHERE invoice.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function selectAllByPurchase(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT
            invoice.id,
            invoice.quantity,
            invoice.total,
            article.name as article_name,
            purchase.id as purchase_id,
            invoice.article_id,
            invoice.purchase_id
            FROM invoice 
            INNER JOIN article ON invoice.article_id=article.id
            INNER JOIN purchase ON invoice.purchase_id=purchase.id
            WHERE purchase.id = :id");
             $statement->bindValue('id', $id, \PDO::PARAM_INT);
             $statement->execute();
             return $statement->fetchAll();
    }
}
