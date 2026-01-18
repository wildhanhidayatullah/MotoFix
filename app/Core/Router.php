<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        $callback = $this->routes[$_SERVER['REQUEST_METHOD']][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            echo "404 - Page Not Found";
            
            return;
        }
        
        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        call_user_func($callback);
    }
}