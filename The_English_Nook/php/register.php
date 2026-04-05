<?php

require 'db_connect.php'; // Pulls in your database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // 1. Hash the password! This scrambles it into a secure string.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Prepare the SQL statement (The '?' act as secure placeholders)
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    
    // 3. Bind the data to the placeholders ("ssss" means 4 strings)
    $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

    // 4. Execute and check if it worked
    if ($stmt->execute()) {
        $message = "Welcome to The English Nook, " . htmlspecialchars($full_name) . "! You can now log in.";
    } else {
        $message = "Error: Could not register. Email might already be in use.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | The English Nook</title>
    </head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2>Create an Account 🦉</h2>
        
        <p style="color: green;"><?php echo $message; ?></p>

        <form action="register.php" method="POST">
            <label>Full Name:</label><br>
            <input type="text" name="full_name" required style="width: 100%; margin-bottom: 10px;"><br>

            <label>Email Address:</label><br>
            <input type="email" name="email" required style="width: 100%; margin-bottom: 10px;"><br>

            <label>Password:</label><br>
            <input type="password" name="password" required style="width: 100%; margin-bottom: 10px;"><br>

            <label>I am a:</label><br>
            <select name="role" style="width: 100%; margin-bottom: 20px;">
                <option value="kid">Kid / Explorer</option>
                <option value="adult">Adult / Scholar</option>
                <option value="teacher">Teacher</option>
            </select><br>

            <button type="submit" style="width: 100%; padding: 10px; background-color: #8b4513; color: white; border: none; border-radius: 4px; cursor: pointer;">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log in here</a>.</p>
    </div>
</body>
</html>