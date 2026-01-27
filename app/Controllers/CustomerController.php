<?php

namespace App\Controllers;

use App\Core\Controller;

class CustomerController extends Controller {
    public function index() {
        $this->view('customers/index', [
            'title' => 'MotoFix | Pelanggan',
            'customers' => $this->Customer->getAll()
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
            csrfCheck();

            if ($this->Customer->create($_POST)) {
                setFlash('Berhasil menambahkan pelanggan baru.', 'success');
            } else {
                setFlash('Gagal menambahkan pelanggan baru.', 'danger');
            }
        }

        redirect('/customers');
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        $customer = $this->Customer->findById($id);

        if (!isset($customer)) {
            setFlash('Pelanggan dengan ID yang dicari tidak ditemukan.', 'danger');
            redirect('/customers');
        }

        $this->view('customers/detail', [
            'title' => 'MotoFix | Informasi Pelanggan & Kendaraan',
            'customer' => $customer,
            'vehicles' => $this->Vehicle->getByCustomerId($id)
        ]);
    }
}
