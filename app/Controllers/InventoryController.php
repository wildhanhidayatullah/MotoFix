<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Item;

class InventoryController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $itemModel = new Item();
        $items = $itemModel->getAll();

        $data = [
            'title' => 'MotoFix | Inventaris',
            'items' => $items
        ];

        $this->view('inventory/index', $data);
    }

    public function create() {
        $data = ['title' => 'MotoFix | Tambah Barang'];
        $this->view('inventory/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemModel = new Item();

            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'stock' => $_POST['stock'],
                'buy_price' => $_POST['buy_price'],
                'sell_price' => $_POST['sell_price'],
                'min_stock_alert' => 5
            ];

            if ($itemModel->create($data)) {
                header('Location: /inventory');
                exit;
            } else {
                echo "Gagal menyimpan data.";
                exit;
            }
        }
    }
}