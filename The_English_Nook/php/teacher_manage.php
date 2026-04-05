<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireRole(['teacher']);
$pageTitle = 'Manage Lessons';
$teacherId = (int)$_SESSION['user_id'];
$classId = (int)($_GET['class_id'] ?? 0);

$checkClass = $pdo->prepare('SELECT id, title FROM classes WHERE id = ? AND teacher_id = ?');
$checkClass->execute([$classId, $teacherId]);
$class = $checkClass->fetch();
if (!$class) {
    exit('Class not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $material = trim($_POST['material_url'] ?? '');
    $video = trim($_POST['video_url'] ?? '');

    if ($title !== '' && $content !== '') {
        $stmt = $pdo->prepare('INSERT INTO lessons (class_id, title, content, material_url, video_url, position_order, has_quiz) VALUES (?, ?, ?, ?, ?, 1, 0)');
        $stmt->execute([$classId, $title, $content, $material, $video]);
    }
}

$listStmt = $pdo->prepare('SELECT id, title FROM lessons WHERE class_id = ? ORDER BY id DESC');
$listStmt->execute([$classId]);
$lessons = $listStmt->fetchAll();

include __DIR__ . '/header.php';
?>
<h1>Manage Lessons: <?php echo htmlspecialchars($class['title']); ?></h1>
<form method="post" class="form-card">
    <label>Lesson title<input type="text" name="title" required></label>
    <label>Lesson content<textarea name="content" rows="4" required></textarea></label>
    <label>Material URL<input type="url" name="material_url"></label>
    <label>Video URL<input type="url" name="video_url"></label>
    <button class="btn" type="submit">Add Lesson</button>
</form>

<h2>Existing Lessons</h2>
<ul>
    <?php foreach ($lessons as $lesson): ?>
        <li><a href="lesson.php?id=<?php echo (int)$lesson['id']; ?>"><?php echo htmlspecialchars($lesson['title']); ?></a></li>
    <?php endforeach; ?>
</ul>
<?php include __DIR__ . '/footer.php'; ?>
