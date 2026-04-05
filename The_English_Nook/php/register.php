<?php
require __DIR__ . '/db.php';
$pageTitle = 'Register';
$message = '';
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
        $errors[] = 'Please enter a valid email.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!in_array($role, ['student', 'teacher'], true)) {
        $role = 'student';
    }

    if (!$errors) {
        $sql = 'INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)';
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Email already exists.';
        }
    }
}

include __DIR__ . '/header.php';
?>
<h1>Create Account</h1>
<?php if ($errors): ?>
    <div class="alert error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
<?php endif; ?>
<form method="post" class="form-card">
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
<?php include __DIR__ . '/footer.php'; ?>
