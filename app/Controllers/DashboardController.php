<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $todayStats = $this->Transaction->getStatsToday();
        $chartData = $this->Transaction->getStatsLastSevenDays();
        $lowStockItems = $this->Item->getLowStockItems();
        $lowStockCount = $this->Item->countLowStock();
        
        $chartLabels = [];
        $chartValues = [];

        foreach ($chartData as $row) {
            $chartLabels[] = date('d M', strtotime($row['t_date']));
            $chartValues[] = $row['total'];
        }

        $data = [
            'title' => 'Dashboard Executive',
            'omzet_today' => $todayStats['total'],
            'trx_count' => $todayStats['count'],
            'low_stock_items' => $lowStockItems,
            'low_stock_total' => $lowStockCount,
            'chart_labels' => json_encode($chartLabels),
            'chart_values' => json_encode($chartValues)
        ];

        $this->view('dashboard', $data);
    }
}