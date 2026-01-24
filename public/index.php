<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\InventoryController;
use App\Controllers\MechanicController;
use App\Controllers\ServiceController;
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
$router->get('/inventory/edit', [InventoryController::class, 'edit']);
$router->get('/inventory/delete', [InventoryController::class, 'delete']);
$router->post('/inventory/store', [InventoryController::class, 'store']);
$router->post('/inventory/update', [InventoryController::class, 'update']);

// Services
$router->get('/services', [ServiceController::class, 'index']);
$router->get('/services/create', [ServiceController::class, 'create']);
$router->get('/services/edit', [ServiceController::class, 'edit']);
$router->get('/services/delete', [ServiceController::class, 'delete']);
$router->post('/services/store', [ServiceController::class, 'store']);
$router->post('/services/update', [ServiceController::class, 'update']);

// Mechanics
$router->get('/mechanics', [MechanicController::class, 'index']);
$router->get('/mechanics/create', [MechanicController::class, 'create']);
$router->get('/mechanics/edit', [MechanicController::class, 'edit']);
$router->get('/mechanics/delete', [MechanicController::class, 'delete']);
$router->post('/mechanics/store', [MechanicController::class, 'store']);
$router->post('/mechanics/update', [MechanicController::class, 'update']);

// Customers
$router->get('/customers', [CustomerController::class, 'index']);
$router->get('/customers/create', [CustomerController::class, 'create']);
$router->post('/customers/store', [CustomerController::class, 'store']);

// Transactions
$router->get('/transactions', [TransactionController::class, 'index']);
$router->get('/transactions/create', [TransactionController::class, 'create']);
$router->get('/transactions/show', [TransactionController::class, 'show']);
$router->post('/transactions/store', [TransactionController::class, 'store']);

// APIs
$router->get('/api/vehicles', [TransactionController::class, 'getVehicles']);

$router->resolve();
