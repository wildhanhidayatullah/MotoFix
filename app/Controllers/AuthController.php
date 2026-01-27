<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller {
    public function __construct() {
        // Do nothing, override to prevent redirect
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            redirect('/');
        }

        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                setFlash('Username dan Password wajib diisi.', 'danger');
                redirect('/login');
            }

            $user = $this->User->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_active'] == 0) {
                    setFlash('Akun Anda telah dinonaktifkan.<br />Hubungi Admin.', 'danger');
                    redirect('/login');
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                redirect('/');
            } else {
                setFlash('Username atau Password salah.', 'danger');
                redirect('/login');
            }
        }
    }

    public function logout() {
        session_destroy();

        redirect('/login');
    }
}
