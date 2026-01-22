<?php

namespace App\Core;

use App\Config\Database;

class Model {
    protected $connection;

    public function __construct() {
        $this->connection = (new Database())->getConnection();
    }
}
