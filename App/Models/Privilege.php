<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Privilege extends \Core\Model
{
    public $table = 'privilege';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, privilege_type FROM privilege');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
