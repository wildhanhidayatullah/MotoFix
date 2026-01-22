<?php

namespace App\Controllers;

use App\Core\Controller;

class ServiceController extends Controller {
    public function index() {
        $this->view('services/index', [
            'title' => 'MotoFix | Layanan',
            'services' => $this->Service->getAll()
        ]);
    }

    public function create() {
        $this->view('services/create', [
            'title' => 'MotoFix | Tambah Layanan'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Service->create($_POST)) {
                setFlash('Berhasil menambahkan layanan baru.', 'success');
                redirect('/services');
            } else {
                setFlash('Gagal menambahkan layanan baru.', 'danger');
                exit;
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        $service = $this->Service->findById($id);
        
        if (!$service) {
            redirect('/services');
        }

        $this->view('services/edit', [
            'title' => 'Edit Layanan',
            'service' => $service
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->Service->update($_POST['id'], $_POST)) {
                setFlash('Berhasil memperbarui layanan.', 'success');
                redirect('/services');
            } else {
                setFlash('Gagal memperbarui layanan.', 'danger');
                exit;
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($this->Service->delete($id)) {
            setFlash('Berhasil menghapus layanan.', 'success');
        } else {
            setFlash('Gagal menghapus layanan.', 'danger');
        }

        redirect('/services');
    }
}
