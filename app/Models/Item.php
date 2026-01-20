<?php

namespace App\Models;

use App\Config\Database;
use PDOException;

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
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO $this->table (code, name, stock, buy_price, sell_price, min_stock_alert)
                VALUES (:code, :name, :stock, :buy_price, :sell_price, :min_stock_alert)"
            );

            $stmt->execute([
                ':code' => $data['code'],
                ':name' => $data['name'],
                ':stock' => $data['stock'],
                ':buy_price' => $data['buy_price'],
                ':sell_price' => $data['sell_price'],
                ':min_stock_alert' => $data['min_stock_alert']
            ]);

            return true;
        } catch (PDOException $error) {
            echo "Failed to commit changes ($this->table): " . $error->getMessage();            
            return false;
        }
    }
}