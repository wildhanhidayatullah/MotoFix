<?php

namespace App\Controllers;

use App\Core\Controller;
use Exception;

class TransactionController extends Controller {
    public function index() {
        $this->view('transactions/index', [
            'title' => 'MotoFix | Transaksi',
            'transactions' => $this->Transaction->getAll()
        ]);
    }

    public function show() {
        $invoice = $_GET['inv'] ?? null;

        if (!$invoice) {
            setFlash('Invoice yang dicari tidak ditemukan.', 'danger');
            redirect('/transactions');
        }

        $header = $this->Transaction->getByInvoice($invoice);

        if (!$header) {
            setFlash('Transaksi dengan invoice yang dicari tidak ditemukan.', 'danger');
            redirect('/transactions');
        }

        $this->view('transactions/show', [
            'title' => 'Invoice ' . $invoice,
            'header' => $header,
            'items' => $items = $this->Item->getByInvoice($invoice)
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
        ini_set('display_errors', 0);
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid Data or Empty Payload']);
                exit;
            }

            if (!isset($input['csrf_token']) || $input['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Security Warning: CSRF Token Mismatch. Silakan refresh halaman.'
                ]);
                exit;
            }

            unset($input['csrf_token']);

            $input['user_id'] = $_SESSION['user_id'];

            $invoice = $this->Transaction->create($input);

            if ($invoice) {
                echo json_encode(['status' => 'success', 'invoice' => $invoice]);
            } else {
                throw new Exception("Gagal menyimpan transaksi.");
            }

            } catch (Exception $error) {

            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => $error->getMessage()
            ]);
        }

        exit;
    }

    public function getVehicles() {
        $vehicles = $this->Vehicle->getByCustomerId($_GET['customer_id'] ?? 0);
        
        header('Content-Type: application/json');
        echo json_encode($vehicles);
        exit;
    }
}
