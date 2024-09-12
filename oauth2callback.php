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

// Create GoogleAuth instance
$googleAuth = new GoogleAuth($client);

// Authenticate user
if (isset($_GET['code'])) {
    // This step fetches user data from Google after authorization
    $userData = $googleAuth->handleGoogleCallback($pdo);
    
    if ($userData) {
        $username = $userData['username'];
        $email = $userData['email'];
        $profile_picture = $userData['profile_picture'];

        // Create User instance
        $userModel = new User($pdo);

        // Check if the user already exists in the database
        $user = $userModel->findUserByEmail($email);

        if ($user) {
            // Log them in
            $_SESSION['user_data'] = [
                'username' => $user['username'],
                'email' => $user['email'],
                'profile_picture' => $user['profile_picture'],
                'role' => $user['role'],
                'created_at' => $user['created_at']
            ];
            header("Location: ./views/dashboard/html/index-2.php?username=");
            exit();
        } else {
            // Create new user
            $random_password = bin2hex(random_bytes(8)); // Generate random password
            $userModel->createUser($username, $email, $random_password);

            // Store user data in session
            $_SESSION['user_data'] = [
                'username' => $username,
                'email' => $email,
                'profile_picture' => $profile_picture,  // Include Google profile picture
                'role' => 'user', // Default role
                'created_at' => date('Y-m-d H:i:s') // Current timestamp
            ];

            header("Location: success.php");
            exit();
        }
    } else {
        // If user data is not returned, handle the error accordingly
        die("Failed to retrieve user data from Google.");
    }
} else {
    // Redirect to Google login if no authorization code is provided
    $googleAuth->redirectToGoogle();
}
?>
