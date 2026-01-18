<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller {
    public function index() {
        $data = [
            'title' => 'Dashboard',
            'user' => 'Admin'
        ];

        $this->view('dashboard', $data);
    }

    public function about() {
        echo "MotoFix adalah personal project yang ditargekan untuk membantu pengelolaan bengkel.";
    }
}
