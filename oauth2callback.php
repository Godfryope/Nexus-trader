<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './vendor/autoload.php'; 
require_once './vendor/vlucas/phpdotenv/src/Dotenv.php';
require_once './config/db.php'; // Include the Database class
require_once './User.php'; // Include the User class
require_once './GoogleAuth.php'; // Include the GoogleAuth class

use Dotenv\Dotenv;

session_start();

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create database instance
$db = new DatabaseConnection($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$pdo = $db->getPdo(); // Use the public method to access the PDO instance

// Google API configuration
$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
$client->addScope('email');
$client->addScope('profile');

// Create User instance
$userModel = new User($pdo);
$googleAuth = new GoogleAuth($client);

// Authenticate user
if (isset($_GET['code'])) {
    $userData = $googleAuth->handleGoogleCallback($pdo);
    
    if ($userData) {
        $username = $userData['username'];
        $email = $userData['email'];

        // Check if the user already exists in the database
        $user = $userModel->findUserByEmail($email);

        if ($user) {
            // User already exists, log them in
            $_SESSION['username'] = $user['username'];
            header("Location: ./dashboard/html/index-2.php?username=" . urlencode($user['username']));
            exit();
        } else {
            // User doesn't exist, create a new user in the database
            $random_password = bin2hex(random_bytes(8)); // Generate random password
            $userModel->createUser($username, $email, $random_password); // Insert user with hashed password

            // Log the user in
            $_SESSION['username'] = $username;
            header("Location: success.php?username=" . urlencode($username));
            exit();
        }
    }
} else {
    // If no authorization code, redirect to Google authentication
    $googleAuth->redirectToGoogle();
}
?>
