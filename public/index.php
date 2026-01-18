<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

Use App\Config\Database;
use App\Controllers\DashboardController;
use App\Core\Router;

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);
$router->get('/about', [DashboardController::class, 'about']);

$router->resolve();
