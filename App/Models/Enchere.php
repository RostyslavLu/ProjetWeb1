<?php

namespace App\Models;

use PDO;

/**
 * Example Enchere model
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Model
{
    public $table = 'Enchere';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM Enchere');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO Enchere ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $db->lastInsertId();
    }
    public static function selectId($value, $field ='id') {
        $db = static::getDB();
        $sql = "SELECT * FROM Enchere WHERE id = :id";
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

    public static function selectMembreId($value, $field ='Membre_id') {
        $db = static::getDB();
        $sql = "SELECT * FROM Enchere WHERE Membre_id = :Membre_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Membre_id", $value);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function selectEncheresArchive() {
        $db = static::getDB();
        $sql = "SELECT * FROM Enchere WHERE date_fin < NOW()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function selectEncheresActuel() {
        $db = static::getDB();
        $sql = "SELECT * FROM Enchere WHERE date_fin > NOW()";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function insertCoupDeCoeur($data) {
        $db = static::getDB();
        $Membre_id = $data['Membre_id'];
        $id = $data['Enchere_id'];

        $sql = "UPDATE Enchere SET coup_de_coer = 1 WHERE Membre_id = :Membre_id AND id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Membre_id", $Membre_id);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }
}