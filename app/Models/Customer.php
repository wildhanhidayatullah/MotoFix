<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;
use PDOException;

class Customer extends Model {
    private $tableC = 'customers';
    private $tableV = 'vehicles';

    public function getAll() {
        $stmt = $this->connection->prepare(
            "SELECT c.*, GROUP_CONCAT(CONCAT(v.brand, ' ', v.model) SEPARATOR ', ') as vehicles_list
             FROM $this->tableC c LEFT JOIN $this->tableV v ON c.id = v.customer_id
             GROUP BY c.id
             ORDER BY c.created_at DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableC WHERE id = :id");

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function create($data) {
        $this->connection->beginTransaction();

        try {
            if (!empty($data['id'])) {
                $customerId = $data['id'];
            } else {
                $stmtCustomer = $this->connection->prepare("INSERT INTO $this->tableC (name, phone) VALUES (:name, :phone)");
            
                $stmtCustomer->execute([
                    ':name' => $data['name'],
                    ':phone' => $data['phone']
                ]);

                $customerId = $this->connection->lastInsertId();
            }
            
            $stmtVehicle = $this->connection->prepare(
                "INSERT INTO $this->tableV (customer_id, brand, model, color, production_year)
                 VALUES (:cid, :brand, :model, :color, :year)"
            );

            $stmtVehicle->execute([
                ':cid' => $customerId,
                ':brand' => $data['brand'],
                ':model' => $data['model'],
                ':color' => $data['color'],
                ':year' => $data['year']
            ]);

            $this->connection->commit();
            return true;
        } catch (PDOException $error) {
            echo "Failed to commit changes ($this->tableC, $this->tableV): " . $error->getMessage();

            $this->connection->rollback();
            return false;
        }
    }
}
