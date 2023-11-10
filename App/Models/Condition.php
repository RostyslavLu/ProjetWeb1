<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Condition extends \Core\Model
{
    public $table = 'Condition';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, type FROM enchere_stempee.condition');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * recouperer une condition par son id
     */
    public static function selectId($value, $field ='id') {
        $db = static::getDB();
        $sql = "SELECT type FROM enchere_stempee.Condition WHERE id = :id";
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
