<?php

namespace App\Controllers;

use App\Core\Controller;

class CustomerController extends Controller {
    public function index() {
        $customers = $this->Customer->getAll();

        $this->view('customers/index', [
            'title' => 'MotoFix | Pelanggan',
            'customers' => $customers
        ]);
    }

    public function create() {
        $this->view('customers/create', [
            'title' => 'MotoFix | Registrasi Pelanggan Baru'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->Customer->create($_POST)) {
                redirect('/customers');
            } else {
                echo "Gagal menyimpan data.";
            }
        }
    }
}
