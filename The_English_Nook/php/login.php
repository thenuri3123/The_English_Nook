<?php

session_start(); // Starts the VIP wristband system
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Find the user by their email
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // 2. Check if the typed password matches the saved hash
        if (password_verify($password, $user['password'])) {
            
            // 3. Success! Save their info into the Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            // 4. Send them to the main page
            header("Location: ../index.html"); 
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | The English Nook</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2>Welcome Back! 📚</h2>
        
        <p style="color: red;"><?php echo $error; ?></p>

        <form action="login.php" method="POST">
            <label>Email Address:</label><br>
            <input type="email" name="email" required style="width: 100%; margin-bottom: 10px;"><br>

            <label>Password:</label><br>
            <input type="password" name="password" required style="width: 100%; margin-bottom: 20px;"><br>

            <button type="submit" style="width: 100%; padding: 10px; background-color: #8b4513; color: white; border: none; border-radius: 4px; cursor: pointer;">Log In</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up here</a>.</p>
    </div>
</body>
</html>