<?php

namespace App\Models;

use App\Config\Database;
use PDOException;

class Customer {
    private $connection;
    private $tableC = 'customers';
    private $tableV = 'vehicles';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->connection->prepare(
            "SELECT $this->tableC.*, GROUP_CONCAT(CONCAT($this->tableV.brand, ' ', $this->tableV.model) SEPARATOR ', ') as vehicles_list
             FROM $this->tableC LEFT JOIN $this->tableV ON $this->tableC.id = $this->tableV.customer_id
             GROUP BY $this->tableC.id
             ORDER BY $this->tableC.created_at DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data) {
        $this->connection->beginTransaction();

        try {
            $stmtCustomer = $this->connection->prepare("INSERT INTO $this->tableC (name, phone) VALUES (:name, :phone)");
            
            $stmtCustomer->execute([
                ':name' => $data['name'],
                ':phone' => $data['phone']
            ]);

            $customerId = $this->connection->lastInsertId();

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
