<?php

namespace App\Models;

use App\Config\Database;
use PDOException;

class Customer {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->connection->prepare(
            "SELECT customers.*, GROUP_CONCAT(CONCAT(vehicles.brand, ' ', vehicles.model) SEPARATOR ', ') as vehicles_list
             FROM customers LEFT JOIN vehicles ON customers.id = vehicles.customer_id
             GROUP BY customers.id
             ORDER BY customers.created_at DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data) {
        $this->connection->beginTransaction();

        try {
            $stmtCustomer = $this->connection->prepare("INSERT INTO customers (name, phone) VALUES (:name, :phone)");
            
            $stmtCustomer->bindParam(':name', $data['name']);
            $stmtCustomer->bindParam(':phone', $data['phone']);
            $stmtCustomer->execute();

            $customerId = $this->connection->lastInsertId();

            $stmtVehicle = $this->connection->prepare(
                "INSERT INTO vehicles (customer_id, brand, model, color, production_year)
                 VALUES (:customer_id, :brand, :model, :color, :year)"
            );

            $stmtVehicle->bindParam(':customer_id', $customerId);
            $stmtVehicle->bindParam(':brand', $data['brand']);
            $stmtVehicle->bindParam(':model', $data['model']);
            $stmtVehicle->bindParam(':color', $data['color']);
            $stmtVehicle->bindParam(':year', $data['year']);
            $stmtVehicle->execute();

            $this->connection->commit();
            return true;
        } catch (PDOException $error) {
            echo "Failed to commit changes (customers, vehicles): " . $error->getMessage();

            $this->connection->rollback();
            return false;
        }
    }
}
