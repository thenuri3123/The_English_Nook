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

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}

include __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>Login for Scholar & Explorer Exam Tracks</h1>
    <p>Use one account for both modes. Your selected Scholar/Explorer theme will continue after login.</p>

    <div class="auth-grid">
        <article class="theme-box exam-level">
            <h3>Scholar Exam Theme 🎓</h3>
            <p>Focused interface for grammar tests, literature analysis, and advanced exam prep.</p>
            <small>Best for O/L, A/L, IELTS and essay practice.</small>
        </article>
        <article class="theme-box exam-level">
            <h3>Explorer Exam Theme 🎈</h3>
            <p>Friendly interface for kids with color-rich quiz and beginner-level test practice.</p>
            <small>Best for alphabet, words, and simple sentence exams.</small>
        </article>
    </div>
</section>

<section class="card form-card">
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
