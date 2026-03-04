<?php

class AuthMiddleware
{
    public static function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /epi-monitor/index.php?page=login');
            exit;
        }
    }

    public static function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function requireAdmin()
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            http_response_code(403);
            echo 'Acesso negado';
            exit;
        }
    }
}
