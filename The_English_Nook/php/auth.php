<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function requireRole(array $roles): void {
    requireLogin();
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        header('Location: dashboard.php');
        exit;
    }
}

function currentUserName(): string {
    return $_SESSION['full_name'] ?? 'Guest';
}
?>
