<?php

namespace App\Core;

class Controller {
    private $models = [];

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
    }

    public function __get($modelName) {
        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }
        
        $modelClass = 'App\\Models\\' . $modelName;
            
        if (class_exists($modelClass)) {
            return $this->models[$modelName] = new $modelClass();
        }

        error_log("ERROR: Model '$modelName' not found. Class: $modelClass");
        http_response_code(500);

        require_once __DIR__ . "/../../views/errors/500.php";
        exit;
    }

    public function view($view, $data = []) {
        extract($data);

        if (file_exists("../views/$view.php")) {
            require_once "../views/$view.php";
        } else {
            error_log("ERROR: View '$view' not found.");
            http_response_code(500);

            require_once __DIR__ . "/../../views/errors/404.php";
        }
    }
}
