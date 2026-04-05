<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The English Nook: English language and literature learning for kids, students, and scholars.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'The English Nook'; ?></title>
    <link rel="stylesheet" href="../css/platform.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="../index.php">The English Nook</a>
    <nav>
        <a href="../index.php">Home</a>
        <a href="classes.php">Classes</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
