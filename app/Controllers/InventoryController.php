<?php

namespace App\Controllers;

use App\Core\Controller;

class InventoryController extends Controller {
    public function index() {
        $items = $this->Item->getAll();

        $this->view('inventory/index', [
            'title' => 'MotoFix | Inventaris',
            'items' => $items
        ]);
    }

    public function create() {
        $this->view('inventory/create', [
            'title' => 'MotoFix | Tambah Barang'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->Item->create($_POST)) {
                redirect('/inventory');
            } else {
                echo "Gagal menyimpan data.";
                exit;
            }
        }
    }
}