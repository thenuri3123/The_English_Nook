<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
$pageTitle = 'Class Details';
$classId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM classes WHERE id = ?');
$stmt->execute([$classId]);
$class = $stmt->fetch();
if (!$class) {
    exit('Class not found.');
}

$lessonStmt = $pdo->prepare('SELECT id, title, has_quiz FROM lessons WHERE class_id = ? ORDER BY position_order');
$lessonStmt->execute([$classId]);
$lessons = $lessonStmt->fetchAll();

include __DIR__ . '/header.php';
?>
<h1><?php echo htmlspecialchars($class['title']); ?></h1>
<p><?php echo htmlspecialchars($class['short_description']); ?></p>
<p><strong>Live session:</strong> <a href="<?php echo htmlspecialchars($class['live_link']); ?>" target="_blank" rel="noopener">Join Link</a></p>
<p><strong>Recordings:</strong> <?php echo htmlspecialchars($class['recording_url']); ?></p>
<a class="btn" href="enroll.php?class_id=<?php echo (int)$class['id']; ?>">Enroll (Simulated Payment)</a>

<h2>Lessons</h2>
<ul>
<?php foreach ($lessons as $lesson): ?>
    <li><a href="lesson.php?id=<?php echo (int)$lesson['id']; ?>"><?php echo htmlspecialchars($lesson['title']); ?></a>
    <?php if ($lesson['has_quiz']): ?>(Quiz)<?php endif; ?></li>
<?php endforeach; ?>
</ul>
<?php include __DIR__ . '/footer.php'; ?>
