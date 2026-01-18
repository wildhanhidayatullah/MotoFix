<?php

namespace App\Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);

        if (file_exists("../views/$view.php")) {
            require_once "../views/$view.php";
        } else {
            die("View '$view' Not Found.");
        }
    }

    public function model($model) {
        require_once "../models/$model.php";
        return new $model();
    }
}
