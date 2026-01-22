<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;
use PDOException;

class Transaction extends Model {
    private $tableT = 'transactions';
    private $tableTD = 'transaction_details';
    private $tableC = 'customers';
    private $tableI = 'items';
    private $tableU = 'users';
    private $tableV = 'vehicles';

    public function getAll() {
        $stmt = $this->connection->prepare(
            "SELECT t.id, t.invoice_number, t.transaction_date, t.total_amount, c.name as customer_name, v.model as vehicle_model, v.brand, u.username as cashier_name
             FROM $this->tableT t JOIN $this->tableC c ON t.customer_id = c.id JOIN $this->tableV v ON t.vehicle_id = v.id JOIN $this->tableU u ON t.user_id = u.id
             ORDER BY t.transaction_date DESC"
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByInvoice($invoiceNumber) {
        $stmt = $this->connection->prepare(
            "SELECT t.*, c.name as customer_name, c.phone, v.brand, v.model, v.color, v.production_year, u.username as cashier_name
             FROM $this->tableT t
             JOIN $this->tableC c ON t.customer_id = c.id
             JOIN $this->tableV v ON t.vehicle_id = v.id
             JOIN $this->tableU u ON t.user_id = u.id
             WHERE t.invoice_number = :inv LIMIT 1"
        );

        $stmt->execute([
            ':inv' => $invoiceNumber
        ]);

        return $stmt->fetch();
    }

    public function create($data) {
        $this->connection->beginTransaction();

        try {
            $invoiceNumber = "INV/" . date('Ym') . "/" . time();

            $stmtHeader = $this->connection->prepare(
                "INSERT INTO $this->tableT (invoice_number, customer_id, vehicle_id, user_id, total_amount, status) 
                 VALUES (:inv, :cid, :vid, :uid, :total, 'completed')"
            );

            $stmtHeader->execute([
                ':inv' => $invoiceNumber,
                ':cid' => $data['customer_id'],
                ':vid' => $data['vehicle_id'],
                ':uid' => $data['user_id'],
                ':total' => $data['grand_total']
            ]);

            $transactionId = $this->connection->lastInsertId();

            $stmtDetail = $this->connection->prepare(
                "INSERT INTO $this->tableTD (transaction_id, item_type, item_id, service_id, mechanic_id, qty, price_at_transaction, subtotal) 
                 VALUES (:tid, :type, :iid, :sid, :mid, :qty, :price, :sub)"
            );

            $stmtStock = $this->connection->prepare("UPDATE $this->tableI SET stock = stock - :qty WHERE id = :id");

            foreach ($data['items'] as $item) {
                $itemId = ($item['type'] == 'part') ? $item['id'] : null;
                $serviceId = ($item['type'] == 'service') ? $item['id'] : null;

                $stmtDetail->execute([
                    ':tid' => $transactionId,
                    ':type' => $item['type'],
                    ':iid' => $itemId,
                    ':sid' => $serviceId,
                    ':mid' => $item['mechanic_id'],
                    ':qty' => $item['qty'],
                    ':price' => $item['price'],
                    ':sub' => $item['subtotal']
                ]);

                if ($item['type'] == 'part') {
                    $stmtStock->execute([
                        ':qty' => $item['qty'],
                        ':id' => $itemId
                    ]);
                }
            }

            $this->connection->commit();
            return $invoiceNumber;
        } catch (PDOException $error) {
            echo "Failed to commit changes ($this->tableT, $this->tableTD, $this->tableI): " . $error->getMessage();

            $this->connection->rollback();
            return false;
        }
    }
}
