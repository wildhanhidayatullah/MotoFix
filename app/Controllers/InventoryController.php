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

    public function edit() {
        $id = $_GET['id'] ?? null;
        $item = $this->Item->findById($id);
        
        if (!$item) {
            redirect('/inventory');
        }

        $this->view('inventory/edit', [
            'title' => 'Edit Barang',
            'item' => $item
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Item->update($_POST['id'], $_POST)) {
                setFlash('Berhasil memperbarui barang.', 'success');
                redirect('/inventory');
            } else {
                setFlash('Gagal memperbarui barang.', 'danger');
                exit;
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if ($this->Item->delete($id)) {
            setFlash('Berhasil menghapus barang.', 'success');
        } else {
            setFlash('Gagal menghapus barang.', 'danger');
        }

        redirect('/inventory');
    }
}