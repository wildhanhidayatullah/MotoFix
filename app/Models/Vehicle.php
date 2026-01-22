<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;

class Vehicle extends Model {
    private $table = 'vehicles';

    public function getByCustomerId($customerId) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE customer_id = :cid");
        $stmt->execute([
            ':cid' => $customerId
        ]);

        return $stmt->fetchAll();
    }
}
