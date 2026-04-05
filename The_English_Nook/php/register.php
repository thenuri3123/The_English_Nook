<?php
require __DIR__ . '/db.php';
$pageTitle = 'Register | The English Nook';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if ($fullName === '' || strlen($fullName) < 2) {
        $errors[] = 'Please enter a valid full name.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!in_array($role, ['student', 'teacher'], true)) {
        $role = 'student';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        try {
            $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Email already exists.';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>Signup for Scholar & Explorer Exam Themes</h1>
    <p>Create one profile and access exam-ready content in both Scholar and Explorer modes.</p>

    <div class="auth-grid">
        <article class="theme-box exam-level">
            <h3>Scholar Path 🎓</h3>
            <p>Academic exam flow with advanced grammar, literature, and writing practice.</p>
            <small>Exam pack: O/L, A/L, IELTS, composition.</small>
        </article>
        <article class="theme-box exam-level">
            <h3>Explorer Path 🎈</h3>
            <p>Playful exam flow for kids with phonics, vocabulary games, and simple tests.</p>
            <small>Exam pack: alphabet, word match, sentence basics.</small>
        </article>
    </div>
</section>

<section class="card form-card">
    <?php if ($errors): ?>
        <div class="alert error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name
            <input type="text" name="full_name" required>
        </label>

        <label>Email
            <input type="email" name="email" required>
        </label>

        <label>Password
            <input type="password" name="password" minlength="8" required>
        </label>

        <label>Role
            <select name="role">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </label>

        <button class="btn" type="submit">Register</button>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
