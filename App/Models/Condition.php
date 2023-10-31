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
}
