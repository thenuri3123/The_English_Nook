<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireLogin();
$pageTitle = 'Dashboard';
$role = $_SESSION['role'];
$userId = (int)$_SESSION['user_id'];

$enrolled = [];
if ($role === 'student') {
    $stmt = $pdo->prepare('SELECT c.id, c.title, c.live_link, c.recording_url, e.payment_status FROM enrollments e JOIN classes c ON c.id = e.class_id WHERE e.user_id = ?');
    $stmt->execute([$userId]);
    $enrolled = $stmt->fetchAll();

    $progressStmt = $pdo->prepare('SELECT l.title, p.status, p.score FROM progress p JOIN lessons l ON l.id = p.lesson_id WHERE p.user_id = ? ORDER BY p.updated_at DESC LIMIT 10');
    $progressStmt->execute([$userId]);
    $progressRows = $progressStmt->fetchAll();
}

if ($role === 'teacher') {
    $teacherClassesStmt = $pdo->prepare('SELECT id, title FROM classes WHERE teacher_id = ?');
    $teacherClassesStmt->execute([$userId]);
    $teacherClasses = $teacherClassesStmt->fetchAll();
}

include __DIR__ . '/header.php';
?>
<h1><?php echo htmlspecialchars(currentUserName()); ?>'s Dashboard</h1>
<?php if (isset($_GET['enrolled'])): ?><div class="alert success">Enrollment successful.</div><?php endif; ?>

<?php if ($role === 'student'): ?>
    <h2>My Enrolled Classes</h2>
    <div class="card-list">
        <?php foreach ($enrolled as $row): ?>
            <article class="panel">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p>Payment: <?php echo htmlspecialchars($row['payment_status']); ?></p>
                <p><a href="<?php echo htmlspecialchars($row['live_link']); ?>" target="_blank" rel="noopener">Live Session Link</a></p>
                <p>Recording: <?php echo htmlspecialchars($row['recording_url']); ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <h2>Progress Tracking</h2>
    <ul>
        <?php foreach ($progressRows as $p): ?>
            <li><?php echo htmlspecialchars($p['title']); ?> - <?php echo htmlspecialchars($p['status']); ?> (Score: <?php echo (int)$p['score']; ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php elseif ($role === 'teacher'): ?>
    <h2>Teacher Tools</h2>
    <p>You can manage your classes and lessons here.</p>
    <ul>
        <?php foreach ($teacherClasses as $c): ?>
            <li><?php echo htmlspecialchars($c['title']); ?> - <a href="teacher_manage.php?class_id=<?php echo (int)$c['id']; ?>">Manage lessons</a></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <h2>Admin Tools</h2>
    <a class="btn" href="admin.php">Open Admin Panel</a>
<?php endif; ?>

<?php include __DIR__ . '/footer.php'; ?>
