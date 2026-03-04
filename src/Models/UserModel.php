<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
