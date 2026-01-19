<?php

namespace App\Models;

use App\Config\Database;

class Item {
    private $connection;
    private $table = 'items';

    public function __construct() {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->connection->prepare(
            "INSERT INTO $this->table (code, name, stock, buy_price, sell_price, min_stock_alert)
             VALUES (:code, :name, :stock, :buy_price, :sell_price, :min_stock_alert)"
        );

        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':buy_price', $data['buy_price']);
        $stmt->bindParam(':sell_price', $data['sell_price']);
        $stmt->bindParam(':min_stock_alert', $data['min_stock_alert']);

        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}