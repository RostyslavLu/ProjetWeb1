<?php

namespace App\Models;

use PDO;

/**
 * 
 * 
 * Images model
 */
class Images extends \Core\Model
{
    public $table = 'Images';
    public $primaryKey = 'id';

    /**
     * Get all the images as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM images');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function insert($data) {
        $db = static::getDB();
        
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $stmt = $db->prepare('INSERT INTO images ('.$fieldName.') VALUES ('.$fieldValue.')');

        foreach($data as $key =>$value){
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
    }
    public static function selectId($value, $field ='Produit_id') {
        $db = static::getDB();
        $sql = "SELECT * FROM images WHERE Produit_id = :Produit_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":Produit_id", $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $stmt = $db->prepare($sql);
        // $stmt->bindValue(":Produit_id", $value);
        // $stmt->execute();
        // $count = $stmt->rowCount();
        // if ($count == 1) {
        //     return $stmt->fetchAll();
        // } else {
        //     header("location:./404.html");
        //     exit;
        // }
    }
}