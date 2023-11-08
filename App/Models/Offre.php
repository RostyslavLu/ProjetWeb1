<?php

namespace App\Models;

use PDO;

/**
 * Example offre model
 *
 * PHP version 7.0
 */
class Offre extends \Core\Model
{
    public $table = 'Offre';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM enchere_stempee.Offre');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO Offre ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function selectOffres($value, $field ='Enchere_id') {
        $db = static::getDB();
        $sql = "SELECT * FROM enchere_stempee.Offre WHERE Enchere_id = :Enchere_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Enchere_id", $value);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function selectOffrePlusEleve($value, $field ='Enchere_id') {
        $db = static::getDB();
        $sql = "SELECT * FROM enchere_stempee.Offre WHERE Enchere_id = :Enchere_id ORDER BY montant DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Enchere_id", $value);
        $stmt->execute();
        return $stmt->fetch();
    }

}