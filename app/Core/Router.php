<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function get($path, $callback) {
        $path = $this->trimPath($path);
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $path = $this->trimPath($path);
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = $this->trimPath($path);

        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->sendNotFound();
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $method = $callback[1];

            call_user_func([$controller, $method]);
        } else {
            call_user_func($callback);
        }
    }

    private function trimPath($path) {
        if ($path !== '/') {
            return rtrim($path, '/');
        }

        return $path;
    }

    private function sendNotFound() {
        http_response_code(404);

        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');

            echo json_encode([
                'status' => 'error',
                'message' => '404 - Not Found',
                'path' => $_SERVER['REQUEST_URI']
            ]);
        } else {
            require_once __DIR__ . "/../../views/errors/404.php";
        }
    }
}