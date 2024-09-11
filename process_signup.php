<?php
// process_signup.php

// Include the database connection logic
require_once __DIR__ . '/config/db.php'; // Assuming you save the DB connection logic in db_connection.php

// Initialize error message variable
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        try {
            // Execute the statement
            $stmt->execute([$username, $email, $hashed_password]);
            header("Location: success.php"); // Redirect to a success page
            exit();
        } catch (PDOException $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}

// Redirect back to the signup page if there's an error
header("Location: signup.php?error=" . urlencode($error));
exit();
