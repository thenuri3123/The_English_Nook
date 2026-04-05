<?php
$servername = "localhost";
$username = "root";       
$password = "";           

// 1. Create connection
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error. "<br>");
}

// 2. Create the Database
$sql = "CREATE DATABASE IF NOT EXISTS english_academy_db";
if ($conn->query($sql) === TRUE) {
  echo "Database 'english_academy_db' created successfully <br>";
} else {
  echo "Error creating database: " . $conn->error. "<br>";
}

// 3. Select the database
$conn->select_db("english_academy_db");

// 4. Create USERS Table (Handles Login/Registration)
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- Passwords must be hashed!
    role VARCHAR(20) DEFAULT 'student' -- Could be 'kid', 'adult', or 'teacher'
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Table 'users' created successfully <br>";
}

// 5. Create VOCABULARY Table (Linked to Users)
$sql_vocab = "CREATE TABLE IF NOT EXISTS vocabulary (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    word VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_vocab) === TRUE) {
    echo "Table 'vocabulary' created successfully <br>";
}

// 6. Create ENROLLMENTS & PAYMENTS Table
$sql_enroll = "CREATE TABLE IF NOT EXISTS enrollments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    course_name VARCHAR(100) NOT NULL,
    payment_status VARCHAR(20) DEFAULT 'Pending', -- 'Pending', 'Paid', 'Failed'
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_enroll) === TRUE) {
    echo "Table 'enrollments' created successfully <br>";
}

$conn->close();
?>