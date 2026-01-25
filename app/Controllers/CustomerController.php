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
        $id = $_GET['id'] ?? null;

        if ($id) {
            $customer = $this->Customer->findById($id);

            $this->view('customers/create', [
                'title' => 'MotoFix | Tambah Kendaraan Pelanggan',
                'customer' => $customer
            ]);
        } else {
            $this->view('customers/create', [
                'title' => 'MotoFix | Registrasi Pelanggan Baru'
            ]);
        }
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

    public function detail() {
        $id = $_GET['id'] ?? null;
        
        $customer = $this->Customer->findById($id);
        $vehicles = $this->Vehicle->getByCustomerId($id);

        $this->view('customers/detail', [
            'title' => 'MotoFix | Informasi Pelanggan & Kendaraan',
            'customer' => $customer,
            'vehicles' => $vehicles
        ]);
    }
}
