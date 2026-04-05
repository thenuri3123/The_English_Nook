<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireLogin();

$pageTitle = 'Dashboard | The English Nook';
$role = $_SESSION['role'] ?? 'student';
$userId = (int)$_SESSION['user_id'];

$enrolledClasses = [];
$progressRows = [];
$teacherClasses = [];

if ($role === 'student') {
    $enrollStmt = $pdo->prepare('SELECT c.title, c.live_link, c.recording_url, e.payment_status FROM enrollments e JOIN classes c ON c.id = e.class_id WHERE e.user_id = ? ORDER BY e.enrolled_at DESC');
    $enrollStmt->execute([$userId]);
    $enrolledClasses = $enrollStmt->fetchAll();

    $progressStmt = $pdo->prepare('SELECT l.title, p.status, p.score FROM progress p JOIN lessons l ON l.id = p.lesson_id WHERE p.user_id = ? ORDER BY p.updated_at DESC LIMIT 8');
    $progressStmt->execute([$userId]);
    $progressRows = $progressStmt->fetchAll();
}

if ($role === 'teacher') {
    $teacherStmt = $pdo->prepare('SELECT id, title, level FROM classes WHERE teacher_id = ? ORDER BY id DESC');
    $teacherStmt->execute([$userId]);
    $teacherClasses = $teacherStmt->fetchAll();
}

include __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>Hello, <?php echo htmlspecialchars($_SESSION['full_name']); ?> 👋</h1>
    <p>Your role: <strong><?php echo htmlspecialchars(ucfirst($role)); ?></strong></p>
</section>

<?php if ($role === 'student'): ?>
    <section class="card">
        <h2>My Enrolled Classes</h2>
        <div class="grid">
            <?php foreach ($enrolledClasses as $class): ?>
                <article class="card">
                    <h3><?php echo htmlspecialchars($class['title']); ?></h3>
                    <p>Payment: <?php echo htmlspecialchars($class['payment_status']); ?></p>
                    <p><a href="<?php echo htmlspecialchars($class['live_link']); ?>" target="_blank" rel="noopener">Join Live Session</a></p>
                    <p>Recording: <?php echo htmlspecialchars($class['recording_url']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="card">
        <h2>Progress Tracking</h2>
        <?php if ($progressRows): ?>
            <ul>
                <?php foreach ($progressRows as $row): ?>
                    <li><?php echo htmlspecialchars($row['title']); ?> — <?php echo htmlspecialchars($row['status']); ?> (Score: <?php echo (int)$row['score']; ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No progress records yet.</p>
        <?php endif; ?>
    </section>
<?php elseif ($role === 'teacher'): ?>
    <section class="card">
        <h2>Manage Your Classes</h2>
        <a href="create_class.php" class="btn" style="margin-bottom: 15px;">+ Create New Class</a>
        <?php if ($teacherClasses): ?>
            <ul>
                <?php foreach ($teacherClasses as $class): ?>
                    <li>
                        <?php echo htmlspecialchars($class['title']); ?> (<?php echo htmlspecialchars($class['level']); ?>)
                        - <a href="teacher_manage.php?class_id=<?php echo (int)$class['id']; ?>">Manage Lessons</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No classes assigned yet.</p>
        <?php endif; ?>
    </section>
<?php else: ?>
    <section class="card">
        <h2>Admin Tools</h2>
        <p>Use the admin panel to manage users, classes, and lessons.</p>
        <a class="btn" href="admin.php">Open Admin Panel</a>
    </section>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
