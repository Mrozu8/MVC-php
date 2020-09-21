<?php

namespace App\Models;

use Core\Model;
use PDO;

class Users extends Model
{
    public static function query($query)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
