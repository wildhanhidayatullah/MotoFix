<?php

namespace App\Controllers;

use App\Core\Controller;

class InventoryController extends Controller {
    public function index() {
        $this->view('inventory/index', [
            'title' => 'MotoFix | Inventaris',
            'items' => $this->Item->getAll()
        ]);
    }

    public function create() {
        $this->view('inventory/create', [
            'title' => 'MotoFix | Tambah Barang'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Item->create($_POST)) {
                setFlash('Berhasil menambahkan barang baru.', 'success');
            } else {
                setFlash('Gagal menambahkan barang baru.', 'danger');
            }
        }

        redirect('/inventory');
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        $item = $this->Item->findById($id);
        
        if (!$item) {
            setFlash('Barang dengan ID yang dicari tidak ditemukan.', 'danger');
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
                setFlash('Berhasil memperbarui data barang.', 'success');
            } else {
                setFlash('Gagal memperbarui data barang.', 'danger');
            }
        }

        redirect('/inventory');
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