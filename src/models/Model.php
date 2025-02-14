<?php

namespace App\models;

use Config\Database;
use Exception;
use PDO;

/**
 * Mon ORM
 */
class Model
{

    protected string $table = self::class;
    protected ?PDO $pdo;
    protected string $keyName = "id";

    public function __construct(string $table = "")
    {
        $this->table = $table ?: $this->table;
        $this->pdo = Database::getPDO();
    }

    /**
     * Retrouve l'instance par son id
     *
     * @param integer $id l'id de l'instance que l'on souhaite trouver
     * @return array | bool un tableau associatif dont les clés sont les colonnes et les valeurs de chaque colonne associée; false si une erreur est rencontrée
     */
    public function find(int $id): array|bool
    {

        // préparer la requête de recherche$this::class
        $table = $this->table;
        $key = $this->keyName;
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $key = :id
        sql;
        try {

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (Exception $e) {
            return false;
        }
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permet de récupérer la première ligne ayant pour valeur de colonne celle passée en paramètre
     *
     * @param string $column la colonne dans laquelle on recherche la valeur
     * @param mixed $value la valeur recherchée pour la colonne
     * @return array | bool  un tableau associatif dont les clés sont les colonnes et les valeurs de chaque colonne associée pour la ligne renvoyée ; false si une erreur est rencontrée
     */
    public function findOneBy(string $column, mixed $value): array|bool
    {
        $table = $this->table;
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $column = :value
        sql;
        try {

            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':value', $value);
            $statement->execute();
        } catch (Exception $e) {
            return false;
        }
        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Permet de récupérer toutes les lignes répondant au critère de recherche
     *
     * @param string $column la colonne dans laquelle on recherche la valeur
     * @param mixed $value la valeur recherchée pour la colonne
     * @return array | bool un tableau indexé contenant toutes les lignes renvoyées; false si une erreur est rencontrée
     */
    public function findAllBy(string $column, mixed $value): array|bool
    {
        $table = $this->table;
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $column = :value
        sql;
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':value', $value);
            $statement->execute();
        } catch (Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Renvoie toutes les lignes du modèle
     *
     * @return array | bool toutes les occurrences de la table; false si une erreur est rencontrée
     */
    public function findAll(): array|bool
    {
        $table = $this->table;
        $sql = <<<sql
        SELECT * 
        FROM $table
        sql;
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
        } catch (Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insère une nouvelle ligne dans la base de données
     *
     * @param array $values
     * @return int|bool le nombre de lignes inséré (normalement une); false si une erreur est rencontrée
     */
    public function create(array $values): int|bool
    {
        $table = $this->table;
        $sql = "INSERT INTO $table (";
        $keys = array_keys($values);
        $keysStr = implode(', ', $keys);
        $sql .= $keysStr . ") VALUES (:";
        $paramsStr = implode(', :', $keys);
        $sql .= $paramsStr . ')';
        try {
            $statement = $this->pdo->prepare($sql);

            foreach ($values as $key => $val) {
                $statement->bindValue(":$key", $val);
            }
            $statement->execute();

            return $this->pdo->lastInsertId();
        }catch (Exception $e) {
            return false;
        }
    }

    /**
     * Met à jour une ligne
     *
     * @param integer $id
     * @param array $values
     * @return integer | bool le nombre de lignes modifié (normalement une); false si une erreur est rencontrée
     */
    public function update(int $id, array $values): int|bool
    {
        $table = $this->table;
        $columns = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($values)));

        $sql = <<<sql
    UPDATE $table SET $columns WHERE {$this->keyName} = :id
    sql;

        try {
            $statement = $this->pdo->prepare($sql);
            foreach ($values as $key => $value) {
                $statement->bindValue(":$key", $value);
            }
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Supprime la ligne correspondant à l'id
     *
     * @param integer $id
     * @return int | bool le nombre de lignes affecté par l'opération (devrait être = 1 sinon, on n'est pas bon :(); false si une erreur est rencontrée
     */
    public function delete(int $id): int|bool
    {
        $table = $this->table;
        $key = $this->keyName;
        $sql = "DELETE FROM $table WHERE $key= :id ;";
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(":$key", $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (Exception $e) {
            return false;
        }
    }

}