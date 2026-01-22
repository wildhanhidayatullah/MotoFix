<?php

namespace App\Controllers;

use App\Core\Controller;

class TransactionController extends Controller {
    public function index() {
        $transactions = $this->Transaction->getAll();

        $this->view('transactions/index', [
            'title' => 'MotoFix | Transaksi',
            'transactions' => $transactions
        ]);
    }

    public function show() {
        $invoice = $_GET['inv'] ?? null;

        if (!$invoice) {
            redirect('/transactions');
        }

        $header = $this->Transaction->getByInvoice($invoice);
        $items = $this->Item->getByInvoice($invoice);

        if (!$header) {
            echo "Transaksi tidak ditemukan.";
            exit;
        }

        $this->view('transactions/show', [
            'title' => 'Invoice ' . $invoice,
            'header' => $header,
            'items' => $items
        ]);
    }

    public function create() {
        $this->view('transactions/create', [
            'title' => 'MotoFix | Transaksi',
            'customers' => $this->Customer->getAll(),
            'items_json' => json_encode($this->Item->getAvailable()),
            'mechanics_json' => json_encode($this->Mechanic->getActive()),
            'services_json' => json_encode($this->Service->getAll())
        ]);
    }

    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid Data']);
            return;
        }

        $input['user_id'] = $_SESSION['user_id'];

        $invoice = $this->Transaction->create($input);

        header('Content-Type: application/json');

        if ($invoice) {
            echo json_encode(['status' => 'success', 'invoice' => $invoice]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi']);
            exit;
        }
    }

    public function getVehicles() {
        $vehicles = $this->Vehicle->getByCustomerId($_GET['customer_id'] ?? 0);
        
        header('Content-Type: application/json');
        echo json_encode($vehicles);
        exit;
    }
}
