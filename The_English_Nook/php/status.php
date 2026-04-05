<?php
// php/status.php
session_start();

// Tell the browser we are sending JSON data, not HTML
header('Content-Type: application/json');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Send back their details
    echo json_encode([
        "logged_in" => true,
        "name" => $_SESSION['user_name'],
        "role" => $_SESSION['user_role']
    ]);
} else {
    // Send back a "not logged in" message
    echo json_encode(["logged_in" => false]);
}
?>