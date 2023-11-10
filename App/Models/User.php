<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
    public $table = 'membre';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, nom, prenom, courriel FROM Membre');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO Membre ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
    }
    public static function selectId($value, $field ='id') {
        $db = static::getDB();
        $sql = "SELECT * FROM membre WHERE id = :id";
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
     * verication est ce que le courriel existe dans la base de donnee
     */
    public static function checkUser($value){
        $db = static::getDB();

        $sql = "SELECT * FROM membre WHERE courriel = :courriel";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":courriel", $value);
        
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 1) {

            return $stmt->fetch();
        } else {
            header("location: login");
            exit;
        }
    }
    public static function insertCoupDeCoeur($Membre_id, $Enchere_id){
        $db = static::getDB();
        $sql = "INSERT INTO Enchere coup_de_coer VALUES 1 WHERE Membre_id = :Membre_id AND Enchere_id = :Enchere_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Membre_id", $Membre_id);
        $stmt->bindValue(":Enchere_id", $Enchere_id);
        $stmt->execute();
    }

}
