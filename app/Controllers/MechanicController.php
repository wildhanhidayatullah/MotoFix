<?php

namespace App\Controllers;

use App\Core\Controller;

class MechanicController extends Controller {
    public function index() {
        $this->view('mechanics/index', [
            'title' => 'MotoFix | Mekanik',
            'mechanics' => $this->Mechanic->getAll()
        ]);
    }

    public function create() {
        $this->view('mechanics/create', [
            'title' => 'MotoFix | Tambah Mekanik'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Mechanic->create($_POST)) {
                setFlash('Berhasil menambahkan mekanik baru.', 'success');
                redirect('/mechanics');
            } else {
                setFlash('Gagal menambahkan mekanik baru.', 'danger');
                exit;
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        $mechanic = $this->Mechanic->findById($id);
        
        if (!$mechanic) {
            redirect('/mechanics');
        }

        $this->view('mechanics/edit', [
            'title' => 'Edit Layanan',
            'mechanic' => $mechanic
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Mechanic->update($_POST['id'], $_POST)) {
                setFlash('Berhasil memperbarui data mekanik.', 'success');
                redirect('/mechanics');
            } else {
                setFlash('Gagal memperbarui data mekanik.', 'danger');
                exit;
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($this->Mechanic->delete($id)) {
            setFlash('Berhasil menghapus data mekanik.', 'success');
        } else {
            setFlash('Gagal menghapus data mekanik.', 'danger');
        }

        redirect('/mechanics');
    }
}
