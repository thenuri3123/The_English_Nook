<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireRole(['admin']);
$pageTitle = 'Admin Panel';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete_user') {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([(int)$_POST['user_id']]);
    }
    if ($action === 'delete_class') {
        $stmt = $pdo->prepare('DELETE FROM classes WHERE id = ?');
        $stmt->execute([(int)$_POST['class_id']]);
    }
}

$users = $pdo->query('SELECT id, full_name, email, role FROM users ORDER BY id DESC')->fetchAll();
$classes = $pdo->query('SELECT id, title, level, price_usd FROM classes ORDER BY id DESC')->fetchAll();
$lessons = $pdo->query('SELECT id, title, class_id FROM lessons ORDER BY id DESC LIMIT 20')->fetchAll();

include __DIR__ . '/header.php';
?>
<h1>Admin Panel</h1>

<h2>Users</h2>
<?php foreach ($users as $user): ?>
<article class="panel">
    <p><?php echo (int)$user['id']; ?> - <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['role']); ?>)</p>
    <form method="post">
        <input type="hidden" name="action" value="delete_user">
        <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
        <button class="btn danger" type="submit">Delete User</button>
    </form>
</article>
<?php endforeach; ?>

<h2>Classes</h2>
<?php foreach ($classes as $class): ?>
<article class="panel">
    <p><?php echo htmlspecialchars($class['title']); ?> (<?php echo htmlspecialchars($class['level']); ?>) - $<?php echo number_format((float)$class['price_usd'], 2); ?></p>
    <form method="post">
        <input type="hidden" name="action" value="delete_class">
        <input type="hidden" name="class_id" value="<?php echo (int)$class['id']; ?>">
        <button class="btn danger" type="submit">Delete Class</button>
    </form>
</article>
<?php endforeach; ?>

<h2>Recent Lessons</h2>
<ul>
    <?php foreach ($lessons as $lesson): ?>
        <li>#<?php echo (int)$lesson['id']; ?> - <?php echo htmlspecialchars($lesson['title']); ?> (Class <?php echo (int)$lesson['class_id']; ?>)</li>
    <?php endforeach; ?>
</ul>

<?php include __DIR__ . '/footer.php'; ?>
