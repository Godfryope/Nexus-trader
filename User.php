<?php
require_once '/var/www/html/bitrader/vendor/autoload.php';

class User {
    private $pdo;

    // Modify constructor to accept a PDO object directly
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
