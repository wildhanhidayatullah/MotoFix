<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;

class User extends Model {
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function findByUsername($username) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE username = :username LIMIT 1");
        
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch();
    }
}
