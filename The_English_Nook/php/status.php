<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged_in' => true,
        'name' => $_SESSION['full_name'],
        'role' => $_SESSION['role']
    ]);
    exit;
}

echo json_encode(['logged_in' => false]);
?>
