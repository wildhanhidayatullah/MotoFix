<?php

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    echo "<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>";
}

function csrfCheck() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    $token = $_POST['csrf_token'] ?? '';

    if (!isset($_SESSION['csrf_token'])) {
        die('Error: Session CSRF Token tidak ditemukan. Silakan refresh halaman.');
    }

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Security Error: CSRF Token tidak valid (Mismatch).');
    }    
}

function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    
    die();
}

function escapeChars($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatNumber(int $value) {
    return number_format($value, 0, ',', '.');
}

function redirect($path) {
    header('Location: ' . $path);
    exit;
}

function setFlash($message, $type) {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

function flash() {
    if (isset($_SESSION['flash'])) {
        echo "<div class='alert alert-{$_SESSION['flash']['type']} alert-dismissible fade show fs-12' role='alert'>
                {$_SESSION['flash']['message']}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    
        unset($_SESSION['flash']);
    }
}
