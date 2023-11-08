<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Produit extends \Core\Model
{
    public $table = 'Produit';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM Produit');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO Produit ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $db->lastInsertId();
    }
    public static function selectId($value, $field ='id') {
        $db = static::getDB();
        $sql = "SELECT * FROM Produit WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $value);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 1) {
            return $stmt->fetch();
        } else {
            header("location:./404.html");
            exit;
        }
    }
    public static function delete($value){
        $db = static::getDB();
        $sql = "DELETE FROM Produit WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $value);
        $stmt->execute();
    }
    public static function search($value){
        $db = static::getDB();
        $sql = "SELECT * FROM Produit WHERE nom LIKE :nom";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":nom", "%".$value."%");
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            return $stmt->fetchAll();
        } else {
            return "";
            exit;
        }
    }
}
