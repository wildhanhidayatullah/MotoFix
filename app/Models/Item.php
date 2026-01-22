<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;
use PDOException;

class Item extends Model {
    private $tableI = 'items';
    private $tableM = 'mechanics';
    private $tableS = 'services';
    private $tableT = 'transactions';
    private $tableTD = 'transaction_details';

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableI ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAvailable() {
        $stmt = $this->connection->prepare("SELECT id, code, name, sell_price, stock FROM $this->tableI WHERE stock > 0 ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByInvoice($invoiceNumber) {
        $stmt = $this->connection->prepare(
            "SELECT td.*, m.name as mechanic_name, COALESCE(i.name, s.name) as item_name, COALESCE(i.code, '-') as item_code
             FROM $this->tableTD td
             JOIN $this->tableT t ON td.transaction_id = t.id
             LEFT JOIN $this->tableI i ON td.item_id = i.id
             LEFT JOIN $this->tableS s ON td.service_id = s.id
             LEFT JOIN $this->tableM m ON td.mechanic_id = m.id
             WHERE t.invoice_number = :inv"
        );

        $stmt->execute([
            ':inv' => $invoiceNumber
        ]);

        return $stmt->fetchAll();
    }

    public function create($data) {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO $this->tableI (code, name, stock, buy_price, sell_price, min_stock_alert)
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
            error_log("ERROR: Failed to commit changes ($this->tableI): " . $error->getMessage()); 
            return false;
        }
    }
}