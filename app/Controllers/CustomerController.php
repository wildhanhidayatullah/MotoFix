<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Customer;

class CustomerController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $customerModel = new Customer();
        $customers = $customerModel->getAll();

        $data = [
            'title' => 'MotoFix | Pelanggan',
            'customers' => $customers
        ];

        $this->view('customers/index', $data);
    }

    public function create() {
        $data = ['title' => 'MotoFix | Registrasi Pelanggan Baru'];
        $this->view('customers/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerModel = new Customer();

            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'brand' => $_POST['brand'],
                'model' => $_POST['model'],
                'color' => $_POST['color'],
                'year' => $_POST['year']
            ];

            if ($customerModel->create($data)) {
                header('Location: /customers');
                exit;
            } else {
                echo "Gagal menyimpan data.";
                exit;
            }
        }
    }
}
