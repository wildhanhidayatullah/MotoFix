<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class Item extends Model {
    private $tableI = 'items';
    private $tableM = 'mechanics';
    private $tableS = 'services';
    private $tableT = 'transactions';
    private $tableTD = 'transaction_details';

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableI WHERE is_deleted = 0 ORDER BY name ASC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAvailable() {
        $stmt = $this->connection->prepare("SELECT id, code, name, sell_price, stock FROM $this->tableI WHERE stock > 0 AND is_deleted = 0 ORDER BY name ASC");
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

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableI WHERE id = :id");

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function create($data) {
        try {
            $stmt = $this->connection->prepare(
                "INSERT INTO $this->tableI (code, name, stock, buy_price, sell_price)
                VALUES (:code, :name, :stock, :buy_price, :sell_price)"
            );

            $stmt->execute([
                ':code' => $data['code'],
                ':name' => $data['name'],
                ':stock' => $data['stock'],
                ':buy_price' => $data['buy_price'],
                ':sell_price' => $data['sell_price'],
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->tableI): " . $error->getMessage()); 
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->connection->prepare("UPDATE $this->tableI SET code = :code, name = :name, stock = :stock, buy_price = :buy_price, sell_price = :sell_price WHERE id = :id AND is_deleted = 0");

            $stmt->execute([
                ':id' => $id,
                ':code' => $data['code'],
                ':name' => $data['name'],
                ':stock' => $data['stock'],
                ':buy_price' => $data['buy_price'],
                ':sell_price' => $data['sell_price']
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->tableI): " . $error->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->connection->prepare("UPDATE $this->tableI SET code = CONCAT('DELETED-', code), is_deleted = 1 WHERE id = :id");

            $stmt->execute([
                ':id' => $id
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->tableI): " . $error->getMessage());
            return false;
        }
    }

    public function getLowStockItems($limit = 5) {
        $stmt = $this->connection->prepare("SELECT name, stock, code FROM items WHERE stock <= 5 AND is_deleted = 0 ORDER BY stock ASC LIMIT " . (int)$limit);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public function countLowStock() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as total FROM items WHERE stock <= 5 AND is_deleted = 0");
        $stmt->execute();
        
        return $stmt->fetch()['total'];
    }
}