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
    /**
     * fonction pour enregistrer timbre dans la base de données
     */
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
    /**
     * fonction pour selectionner un timbre par id
     */
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
    /**
     * fonction pour selectionner un timbre par id d'enchère
     */
    public static function selectEnchereId($value, $field ='Enchere_id') {
        $db = static::getDB();
        $sql = "SELECT * FROM Produit WHERE Enchere_id = :Enchere_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Enchere_id", $value);
        $stmt->execute();
        $count = $stmt->rowCount();

        return $stmt->fetchAll();

    }

    /**
     * fonction pour effacer un timbre de la base de données par id
     */
    public static function delete($value){
        $db = static::getDB();
        $sql = "DELETE FROM Produit WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $value);
        $stmt->execute();
    }
    /**
     * fonction pour rechercher un timbre par nom
     */
    public static function search($value){
        $db = static::getDB();
        $sql = "SELECT * FROM Produit WHERE nom LIKE :nom";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":nom", "%".$value."%");
        $stmt->execute();

        return $stmt->fetchAll();
    }
    /**
     * fonction pour rechercher un timbre par les critères
     */
    public static function searchComplex($condition, $type, $anneMin, $anneMax, $tirage, $certification){
        
        $db = static::getDB();
        $sql = "SELECT * FROM Produit WHERE Condition_Id LIKE :condition AND Type_id LIKE :type AND date_creation BETWEEN :anneMin AND :anneMax AND tirage LIKE :tirage AND certifie LIKE :certification";
        $stmt = $db->prepare($sql);
 
        $stmt->bindValue(":condition", "%".$condition."%");
        
        $stmt->bindValue(":type", "%".$type."%");
        $stmt->bindValue(":anneMin", $anneMin);
        $stmt->bindValue(":anneMax", $anneMax);
        $stmt->bindValue(":tirage", "%".$tirage."%");
        $stmt->bindValue(":certification", "%".$certification."%");
        $stmt->execute();
        $stmt->fetchAll();
        print_r($stmt);
        die();

        return $stmt->fetchAll();
    }
}
