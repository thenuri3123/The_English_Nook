<?php
require __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Login | The English Nook';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, full_name, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        // Redirect to a page that uses the same shared header/footer/styles.
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}

include __DIR__ . '/../includes/header.php';
?>
<section class="card form-card">
    <h1>Welcome Back</h1>
    <p>Login to continue your English learning journey.</p>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert success">Registration successful. Please login.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button class="btn" type="submit">Login</button>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
