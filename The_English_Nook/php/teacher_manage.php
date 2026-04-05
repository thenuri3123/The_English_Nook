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

include __DIR__ . '/../includes/header.php';
?>
<h1>Manage Lessons: <?php echo htmlspecialchars($class['title']); ?></h1>
<form method="post" class="form-card">
    <label>Lesson title<input type="text" name="title" required></label>
    <label>Lesson content<textarea name="content" rows="4" required></textarea></label>
    <label>Material URL<input type="url" name="material_url"></label>
    <label>Video URL<input type="url" name="video_url"></label>
    <button class="btn" type="submit">Add Lesson</button>
</form>

<?php 
$studentStmt = $pdo->prepare('SELECT u.full_name, e.payment_status FROM enrollments e JOIN users u ON u.id = e.user_id WHERE e.class_id = ?');
$studentStmt->execute([$classId]);
$students = $studentStmt->fetchAll();
?>

<h2>Enrolled Students</h2>
<?php if ($students): ?>
    <table style="width:100%; border-collapse: collapse; margin-top:10px;">
        <thead>
            <tr style="text-align:left; border-bottom: 2px solid #ddd;">
                <th style="padding:10px;">Student Name</th>
                <th style="padding:10px;">Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $s): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding:10px;"><?php echo htmlspecialchars($s['full_name']); ?></td>
                    <td style="padding:10px;"><span class="badge" style="background:<?php echo $s['payment_status'] === 'paid' ? '#e8f5e9' : '#fff3e0'; ?>; color:<?php echo $s['payment_status'] === 'paid' ? '#2e7d32' : '#e65100'; ?>;"><?php echo htmlspecialchars($s['payment_status']); ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No students enrolled yet.</p>
<?php endif; ?>

<h2>Existing Lessons</h2>
<ul>
    <?php foreach ($lessons as $lesson): ?>
        <li><a href="lesson.php?id=<?php echo (int)$lesson['id']; ?>"><?php echo htmlspecialchars($lesson['title']); ?></a></li>
    <?php endforeach; ?>
</ul>
<?php include __DIR__ . '/../includes/footer.php'; ?>
