<?php
declare(strict_types=1);

function session_boot(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Strict']);
        session_start();
    }
}

function auth_check(): void {
    session_boot();
    if (empty($_SESSION['admin'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function auth_user(): array {
    return $_SESSION['admin'] ?? [];
}

function flash_set(string $key, string $msg): void {
    $_SESSION['_flash'][$key] = $msg;
}

function flash_get(string $key): string {
    $v = $_SESSION['_flash'][$key] ?? '';
    unset($_SESSION['_flash'][$key]);
    return $v;
}

function csrf_token(): string {
    session_boot();
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void {
    if (($_POST['_csrf'] ?? '') !== csrf_token()) {
        http_response_code(403); die('CSRF token tidak valid.');
    }
}

function redirect(string $url): never {
    header('Location: ' . $url); exit;
}

function post(string $k, string $default = ''): string {
    return trim((string)($_POST[$k] ?? $default));
}

function old(string $k, string $default = ''): string {
    return h($_SESSION['_old'][$k] ?? $default);
}

function old_set(array $data): void {
    $_SESSION['_old'] = $data;
}

function old_clear(): void {
    unset($_SESSION['_old']);
}
