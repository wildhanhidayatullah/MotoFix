<?php

namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller {
    public function index() {
        $this->view('users/index', [
            'title' => 'MotoFix | Pengguna',
            'users' => $this->User->getAll()
        ]);
    }

    public function create() {
        $this->view('users/create', [
            'title' => 'MotoFix | Tambah Pengguna'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->User->create($_POST)) {
                setFlash('Berhasil menambahkan pengguna baru.', 'success');
                redirect('/users');
            } else {
                setFlash('Gagal menambahkan pengguna baru.', 'danger');
                exit;
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        $user = $this->User->findById($id);
        
        if (!isset($user)) {
            redirect('/users');
        }

        $this->view('users/edit', [
            'title' => 'Edit Layanan',
            'user' => $user
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            if ($this->User->update($_POST['id'], $_POST)) {
                setFlash('Berhasil memperbarui data pengguna.', 'success');
                redirect('/users');
            } else {
                setFlash('Gagal memperbarui data pengguna.', 'danger');
                exit;
            }
        }
    }

    public function changeStatus() {
        $id = $_GET['id'] ?? null;

        if ($this->User->updateStatus($id)) {
            setFlash('Berhasil mengubah status pengguna.', 'success');
        } else {
            setFlash('Gagal mengubah status pengguna.', 'danger');
        }

        redirect('/users');
    }
}
