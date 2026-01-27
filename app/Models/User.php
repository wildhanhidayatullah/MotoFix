<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

class User extends Model {
    private $table = 'users';

    public function getAll() {
        $stmt = $this->connection->prepare("SELECT id, username, role, is_active, created_at FROM $this->table ORDER BY username ASC");

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE id = :id");
        
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }
    
    public function findByUsername($username) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE username = :username");
        
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch();
    }

    public function create($data) {
        try {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt = $this->connection->prepare("INSERT INTO $this->table (username, password, role) VALUES (:user, :pass, :role)");
            
            $stmt->execute([
                ':user' => $data['username'],
                ':pass' => $hash,
                ':role' => $data['role']
            ]);

            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "UPDATE $this->table SET username = :user, role = :role, password_hash = :pass WHERE id = :id";
            $params = [
                ':id' => $id,
                ':user' => $data['username'],
                ':role' => $data['role'],
                ':pass' => $hash
            ];
        } else {
            $query = "UPDATE $this->table SET username = :user, role = :role WHERE id = :id";
            $params = [
                ':id' => $id,
                ':user' => $data['username'],
                ':role' => $data['role']
            ];
        }

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);

            $stmtStatus = $this->connection->prepare("SELECT is_active FROM $this->table WHERE id = :id");
            $stmtStatus->execute([':id' => $id]);
            $status = $stmtStatus->fetch();

            if ($data['is_active'] !== $status['is_active']) {
                if ($id == $_SESSION['user_id']) {
                    
                } else {
                    $stmt = $this->connection->prepare("UPDATE $this->table SET is_active = :active WHERE id = :id");
                    $stmt->execute([
                        ':id' => $id,
                        ':active' => $data['is_active']
                    ]);
                }
            }
    
            return true;
        } catch (PDOException $error) {
            error_log("ERROR: Failed to commit changes ($this->table): " . $error->getMessage());
            return false;
        }
    }
}
