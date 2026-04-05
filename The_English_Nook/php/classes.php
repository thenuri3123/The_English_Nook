<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
$pageTitle = 'Classes';

$classes = $pdo->query('SELECT id, title, level, price_usd, short_description FROM classes ORDER BY level, id')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<h1>Available Classes</h1>
<div class="grid">
<?php foreach ($classes as $class): ?>
    <article class="card">
        <h3><?php echo htmlspecialchars($class['title']); ?></h3>
        <span class="badge"><?php echo htmlspecialchars($class['level']); ?></span>
        <p><?php echo htmlspecialchars($class['short_description']); ?></p>
        <p><strong>Price:</strong> $<?php echo number_format((float)$class['price_usd'], 2); ?></p>
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <a class="btn" href="class_details.php?id=<?php echo (int)$class['id']; ?>">Syllabus</a>
            <a class="btn" href="enroll.php?class_id=<?php echo (int)$class['id']; ?>" style="background:var(--secondary-color); color:#000;">Enroll Now</a>
        </div>
    </article>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
