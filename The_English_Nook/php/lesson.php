<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireLogin();
$pageTitle = 'Lesson';
$userId = (int)$_SESSION['user_id'];
$lessonId = (int)($_GET['id'] ?? 0);

$lessonStmt = $pdo->prepare('SELECT * FROM lessons WHERE id = ?');
$lessonStmt->execute([$lessonId]);
$lesson = $lessonStmt->fetch();
if (!$lesson) {
    exit('Lesson not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $commentText = trim($_POST['comment_text']);
    if ($commentText !== '') {
        $commentStmt = $pdo->prepare('INSERT INTO comments (lesson_id, user_id, comment_text) VALUES (?, ?, ?)');
        $commentStmt->execute([$lessonId, $userId, $commentText]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_complete'])) {
    $progressStmt = $pdo->prepare('INSERT INTO progress (user_id, lesson_id, status, score) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = VALUES(status), score = VALUES(score), updated_at = CURRENT_TIMESTAMP');
    $progressStmt->execute([$userId, $lessonId, 'completed', (int)($_POST['score'] ?? 0)]);
}

$commentsStmt = $pdo->prepare('SELECT c.comment_text, c.created_at, u.full_name FROM comments c JOIN users u ON u.id = c.user_id WHERE lesson_id = ? ORDER BY c.created_at DESC');
$commentsStmt->execute([$lessonId]);
$comments = $commentsStmt->fetchAll();

$quizStmt = $pdo->prepare('SELECT id, question_text, option_a, option_b, option_c, option_d, correct_option FROM quizzes WHERE lesson_id = ? ORDER BY id');
$quizStmt->execute([$lessonId]);
$quizRows = $quizStmt->fetchAll();

include __DIR__ . '/header.php';
?>
<h1><?php echo htmlspecialchars($lesson['title']); ?></h1>
<p><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></p>

<?php if ($lesson['material_url']): ?><p>Material: <a href="<?php echo htmlspecialchars($lesson['material_url']); ?>" target="_blank" rel="noopener">Open PDF/Doc</a></p><?php endif; ?>
<?php if ($lesson['video_url']): ?><p>Recording: <a href="<?php echo htmlspecialchars($lesson['video_url']); ?>" target="_blank" rel="noopener">Watch video</a></p><?php endif; ?>

<?php if ($quizRows): ?>
<h2>Quiz</h2>
<form id="quiz-form">
    <?php foreach ($quizRows as $i => $q): ?>
        <fieldset class="panel">
            <legend><?php echo ($i + 1) . '. ' . htmlspecialchars($q['question_text']); ?></legend>
            <?php foreach (['a','b','c','d'] as $opt): ?>
                <label><input type="radio" name="q_<?php echo (int)$q['id']; ?>" value="<?php echo $opt; ?>"> <?php echo htmlspecialchars($q['option_'.$opt]); ?></label><br>
            <?php endforeach; ?>
            <input type="hidden" name="correct_<?php echo (int)$q['id']; ?>" value="<?php echo htmlspecialchars($q['correct_option']); ?>">
        </fieldset>
    <?php endforeach; ?>
    <button type="button" class="btn" onclick="gradeQuiz()">Check Answers</button>
</form>
<div id="quiz-result"></div>
<form method="post" id="progress-form">
    <input type="hidden" name="score" id="score-field" value="0">
    <button class="btn" type="submit" name="mark_complete" value="1">Save Progress</button>
</form>
<?php endif; ?>

<h2>Discussion</h2>
<form method="post" class="form-card">
    <label>Add Comment
        <textarea name="comment_text" rows="3" required></textarea>
    </label>
    <button type="submit" class="btn">Post</button>
</form>
<?php foreach ($comments as $comment): ?>
    <article class="panel">
        <strong><?php echo htmlspecialchars($comment['full_name']); ?></strong>
        <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
        <small><?php echo htmlspecialchars($comment['created_at']); ?></small>
    </article>
<?php endforeach; ?>

<script>
function gradeQuiz() {
    const form = document.getElementById('quiz-form');
    const fields = form.querySelectorAll('input[type="hidden"][name^="correct_"]');
    let score = 0;

    fields.forEach((field) => {
        const id = field.name.replace('correct_', '');
        const chosen = form.querySelector(`input[name="q_${id}"]:checked`);
        if (chosen && chosen.value === field.value) {
            score += 1;
        }
    });

    document.getElementById('quiz-result').textContent = `Score: ${score} / ${fields.length}`;
    document.getElementById('score-field').value = score;
}
</script>
<?php include __DIR__ . '/footer.php'; ?>
