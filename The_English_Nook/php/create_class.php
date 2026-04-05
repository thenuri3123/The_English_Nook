<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
requireRole(['teacher']);

$pageTitle = 'Create New Class | The English Nook';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $level = $_POST['level'] ?? 'beginner';
    $category = $_POST['category'] ?? 'scholar';
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0.00);
    $live_link = trim($_POST['live_link'] ?? '');
    $teacher_id = (int)$_SESSION['user_id'];

    if ($title === '') $errors[] = 'Class title is required.';
    if ($description === '') $errors[] = 'Description is required.';

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO classes (teacher_id, title, level, category, short_description, price_usd, live_link) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$teacher_id, $title, $level, $category, $description, $price, $live_link]);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Failed to create class: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<section class="card">
    <h1>Create a New Class</h1>
    <p>Set up a new course for your students. You can add lessons later from your dashboard.</p>
</section>

<section class="card form-card">
    <?php if ($success): ?>
        <div class="alert success">Class created successfully! <a href="dashboard.php">Go to Dashboard</a></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Class Title
            <input type="text" name="title" placeholder="e.g. Grade 10 Grammar Masterclass" required>
        </label>

        <label>Category (Path)
            <select name="category">
                <option value="scholar">Scholar Path 🎓 (Adults/Academic)</option>
                <option value="explorer">Explorer Path 🎈 (Kids/Playful)</option>
            </select>
        </label>

        <label>Level
            <select name="level">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </label>

        <label>Short Description
            <textarea name="description" rows="3" placeholder="What will students learn in this class?" required></textarea>
        </label>

        <label>Price (USD)
            <input type="number" name="price" step="0.01" value="0.00">
        </label>

        <label>Live Session Link (Zoom/Google Meet)
            <input type="url" name="live_link" placeholder="https://zoom.us/j/...">
        </label>

        <button class="btn" type="submit">Create Class</button>
        <a href="dashboard.php" class="btn" style="background: #666; margin-left: 10px;">Cancel</a>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
