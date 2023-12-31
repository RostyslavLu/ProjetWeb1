<?php

namespace App\Models;

use PDO;

/**
 * Example Encherfavorit model
 *
 * PHP version 7.0
 */
class Encherfavorit extends \Core\Model
{
    public $table = 'Encherfavorit';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM enchere_stempee.Encherfavorit');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO Encherfavorit ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $db->lastInsertId();
    }
    /**
     * fonction pour recouperer les enchères favorites d'un utilisateur
     */
    public static function selectEncherfavorit($Membre_id) {
        $db = static::getDB();
        $sql = "SELECT * FROM Encherfavorit WHERE Membre_id = :Membre_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Membre_id", $Membre_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            
            return $stmt->fetchAll();
        } else {
            return null;
        }

    }
    /**
     * fonction pour recouperer les enchères favorites d'un utilisateur
     */
    public static function selectUserEncheresFavorit($id, $userId) {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM Encherfavorit WHERE Enchere_id = :id AND Membre_id = :userId');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * fonction pour supprimer une enchère des favoris
     */
    public static function delete($id, $userId) {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM Encherfavorit WHERE Enchere_id = :id AND Membre_id = :userId');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}