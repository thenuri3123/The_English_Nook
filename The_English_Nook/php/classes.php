<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
$pageTitle = 'Classes';

$classes = $pdo->query('SELECT id, title, level, price_usd, short_description FROM classes ORDER BY level, id')->fetchAll();
include __DIR__ . '/header.php';
?>
<h1>Available Classes</h1>
<div class="card-list">
<?php foreach ($classes as $class): ?>
    <article class="panel">
        <h2><?php echo htmlspecialchars($class['title']); ?></h2>
        <p><strong>Level:</strong> <?php echo htmlspecialchars($class['level']); ?></p>
        <p><?php echo htmlspecialchars($class['short_description']); ?></p>
        <p><strong>Price:</strong> $<?php echo number_format((float)$class['price_usd'], 2); ?></p>
        <a class="btn" href="class_details.php?id=<?php echo (int)$class['id']; ?>">View Details</a>
    </article>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/footer.php'; ?>
