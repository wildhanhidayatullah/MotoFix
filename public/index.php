<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\InventoryController;
use App\Controllers\CustomerController;
use App\Controllers\TransactionController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$router = new Router();

// ---| ROUTE LIST |---
// Auth
$router->get('/login', [AuthController::class, 'index']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->post('/login-process', [AuthController::class, 'login']);

// Dashboard
$router->get('/', [DashboardController::class, 'index']);
$router->get('/about', [DashboardController::class, 'about']);

// Invetory
$router->get('/inventory', [InventoryController::class, 'index']);
$router->get('/inventory/create', [InventoryController::class, 'create']);
$router->post('/inventory/store', [InventoryController::class, 'store']);

// Customers
$router->get('/customers', [CustomerController::class, 'index']);
$router->get('/customers/create', [CustomerController::class, 'create']);
$router->post('/customers/store', [CustomerController::class, 'store']);

// Transactions
$router->get('/transactions', [TransactionController::class, 'index']);
$router->get('/transactions/create', [TransactionController::class, 'create']);
$router->get('/api/vehicles', [TransactionController::class, 'getVehicles']);
$router->post('/transactions/store', [TransactionController::class, 'store']);

$router->resolve();
