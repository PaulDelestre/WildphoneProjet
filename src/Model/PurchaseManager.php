<?php

namespace App\Model;

/**
 *
 */
class PurchaseManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'purchase';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $data): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (user_id, total, created_at) VALUES (:user_id, :total, :created_at)");
        $statement->bindValue('user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('total', $data['total'], \PDO::PARAM_INT);
        $statement->bindValue('created_at', $data['created_at']);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT
            purchase.id,
            purchase.total,
            purchase.created_at,
            purchase.user_id as purchase_user_id,
            purchase.user_id
            FROM purchase
            INNER JOIN user ON purchase.user_id=user.id")->fetchAll();
    }
    
    public function selectAllByUser($user_id): array
    {
        $statement = $this->pdo->prepare("SELECT
        purchase.id,
        purchase.total,
        purchase.created_at,
        purchase.user_id as purchase_user_id,
        purchase.user_id
        FROM purchase
        INNER JOIN user ON purchase.user_id=user.id
        WHERE purchase.user_id =:user_id");
        $statement->bindValue('user_id', $user_id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectOneById(int $id)
    {
            $statement = $this->pdo->prepare("SELECT
            purchase.id,
            purchase.total,
            purchase.created_at,
            purchase.user_id as purchase_user_id,
            purchase.user_id
            FROM purchase
            INNER JOIN user ON purchase.user_id=user.id
            WHERE purchase.id=:id");
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch();
    }
}
