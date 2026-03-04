<?php
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login(string $email, string $password): bool {
        $user = $this->userModel->findByEmail($email);
        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function logout(): void {
        session_destroy();
        header('Location: /index.php?page=login');
        exit;
    }
}
