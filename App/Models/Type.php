<?php

namespace App\Models;

use PDO;

/**
 * Example type model
 *
 * PHP version 7.0
 */
class Type extends \Core\Model
{
    public $table = 'Type';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, type_nom FROM enchere_stempee.type');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function selectId($value, $field ='id') {
        $db = static::getDB();
        $sql = "SELECT type_nom FROM enchere_stempee.Type WHERE id = :id";
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
}