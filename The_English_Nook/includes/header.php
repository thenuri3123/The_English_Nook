<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$isPhpPage = strpos($_SERVER['PHP_SELF'], '/php/') !== false;
$prefix = $isPhpPage ? '../' : '';

function isActive(string $file, string $currentPage): string {
    return $file === $currentPage ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The English Nook: fun and structured English learning platform.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'The English Nook'; ?></title>
    <link rel="stylesheet" href="<?php echo $prefix; ?>includes/styles.css">
</head>
<body>
<header class="site-header">
    <div class="nav-wrap">
        <a class="brand" href="<?php echo $prefix; ?>index.php">The English Nook</a>
        <nav class="nav-links">
            <a class="nav-link <?php echo isActive('index.php', $currentPage); ?>" href="<?php echo $prefix; ?>index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="nav-link <?php echo isActive('dashboard.php', $currentPage); ?>" href="<?php echo $prefix; ?>php/dashboard.php">Dashboard</a>
                <a class="nav-link" href="<?php echo $prefix; ?>php/logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link <?php echo isActive('login.php', $currentPage); ?>" href="<?php echo $prefix; ?>php/login.php">Login</a>
                <a class="nav-link <?php echo isActive('register.php', $currentPage); ?>" href="<?php echo $prefix; ?>php/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
