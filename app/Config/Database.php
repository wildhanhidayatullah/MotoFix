<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    public $connection;

    public function getConnection() {
        $this->connection = null;

        try {
            $db_host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASS'];

            define("DSN", sprintf("mysql:host=%s;dbname=%s", $db_host, $db_name));

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ];

            $this->connection = new PDO(DSN, $username, $password, $options);
        } catch (PDOException $error) {
            echo "Connection Error: " . $error->getMessage();
        }

        return $this->connection;
    }
}
