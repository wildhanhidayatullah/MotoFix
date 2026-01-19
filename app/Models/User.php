<?php

namespace App\Models;

use App\Config\Database;

class User {
    private $connection;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function findByUsername($username) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE username = :username LIMIT 1");
        
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch();
    }
}
