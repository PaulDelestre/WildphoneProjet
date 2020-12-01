<?php

namespace App\Model;

/**
 *
 */
class UserManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search(string $email)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetchObject();
        if ($user) {
            return $user;
        }
        return false;
    }


    /**
     * @param array $user
     * @return int
     */
    public function insert(array $user): int
    {
        // prepared request
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " 
            (`firstname`,`lastname`,`email`,`password`,`address`,`role_id`) 
            VALUES 
            (:firstname, :lastname, :email, :password, :address, :role_id)"
        );
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('address', $user['address'], \PDO::PARAM_STR);
        $statement->bindValue('role_id', $user['role_id'], \PDO::PARAM_INT);

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


    /**
     * @param array $user
     * @return bool
     */


    public function update(array $user): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `id` = :id,
            `firstname` = :firstname,
            `lastname` = :lastname,
            `email` = :email,
            `password` = :password,
            `address` = :address,
            `role_id` = :role_id
            WHERE id=:id"
        );
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('address', $user['address'], \PDO::PARAM_STR);
        $statement->bindValue('role_id', $user['role_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT 
            user.id,
            user.firstname,
            user.lastname,
            user.email,
            user.password,
            user.address,
            user.role_id
            FROM user 
            INNER JOIN role ON user.role_id=role.id")->fetchAll();
    }

    public function selectOneWithDetails(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT 
        user.id,
        user.firstname,
        user.lastname,
        user.email,
        user.password,
        user.address,
        user.role_id
        FROM user 
        INNER JOIN role ON user.role_id=role.id
        WHERE user.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function updateUser(array $user): bool
    {

        // prepared request
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET
            `id` = :id,
            `firstname` = :firstname,
            `lastname` = :lastname,
            `password` = :password,
            `address` = :address
            WHERE id=:id"
        );
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('address', $user['address'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
