<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$classId = (int)($_GET['class_id'] ?? 0);

if ($classId < 1) {
    header('Location: classes.php');
    exit;
}

$check = $pdo->prepare('SELECT id FROM enrollments WHERE user_id = ? AND class_id = ?');
$check->execute([$userId, $classId]);

if (!$check->fetch()) {
    $stmt = $pdo->prepare('INSERT INTO enrollments (user_id, class_id, payment_status, amount_paid) VALUES (?, ?, ?, (SELECT price_usd FROM classes WHERE id = ?))');
    $stmt->execute([$userId, $classId, 'paid', $classId]);
}

header('Location: dashboard.php?enrolled=1');
exit;
