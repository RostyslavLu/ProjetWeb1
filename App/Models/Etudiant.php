<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Etudiant extends \Core\Model
{
    public $table = 'etudiant';
    public $primaryKey = 'id';

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT id, nom, age FROM etudiant');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
