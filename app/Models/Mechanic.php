<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Mechanic extends Model {
    private $table = 'mechanics';

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getActive() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE is_active = 1");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        
        return $stmt->fetch();
    }

    public function create($data) {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO mechanics (name, phone, base_salary, commission_rate, is_active) 
                 VALUES (:name, :phone, :salary, :rate, 1)"
            );
        
            $stmt->execute([
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':salary' => $data['base_salary'],
                ':rate' => $data['commission_rate']
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->connection->prepare("UPDATE mechanics SET name = :name, phone = :phone, base_salary = :salary, commission_rate = :rate, is_active = :active WHERE id = :id");

            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':salary' => $data['base_salary'],
                ':rate' => $data['commission_rate'],
                ':active' => $data['is_active']
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }
}
