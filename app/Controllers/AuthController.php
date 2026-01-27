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
                $_SESSION['error'] = "Username dan Password wajib diisi!";
                
                redirect('/login');
            }

            $user = $this->User->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                redirect('/');
            } else {
                $_SESSION['error'] = "Username atau Password salah!";

                redirect('/login');
            }
        }
    }

    public function logout() {
        session_destroy();

        redirect('/login');
    }
}
