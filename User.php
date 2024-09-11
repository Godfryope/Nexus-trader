<?php
require_once '/var/www/html/bitrader/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class DatabaseConnection {
    private $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->pdo = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->logError("Database connection failed: " . $e->getMessage());
            die("Database connection failed.");
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    private function logError($message) {
        error_log($message);
        echo "<script>console.error('PHP Error: " . addslashes($message) . "');</script>";
    }
}

class User {
    private $pdo;

    public function __construct(DatabaseConnection $dbConnection) {
        $this->pdo = $dbConnection->getPdo();
    }

    // Find a user by email
    public function findUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function createUser($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }
}
?>
