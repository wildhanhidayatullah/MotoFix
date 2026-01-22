<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Service extends Model {
    private $table = 'services';

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * from $this->table ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * from $this->table WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function create($data) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO $this->table (name, price) VALUES (:name, :price)");
            
            $stmt->execute([
                ':name' => $data['name'],
                ':price' => $data['price']
            ]);
    
            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->connection->prepare("UPDATE $this->table SET name = :name, price = :price WHERE id = :id");

            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':price' => $data['price']
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM $this->table WHERE id = :id");

            $stmt->execute([
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }
}
