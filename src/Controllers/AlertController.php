<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class AlertController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll(int $limit = 100): array {
        AuthMiddleware::requireAuth();
        return $this->pdo->query(
            "SELECT a.*, c.name as camera_name FROM alerts a
             JOIN cameras c ON a.camera_id = c.id
             ORDER BY a.created_at DESC LIMIT $limit"
        )->fetchAll();
    }

    public function acknowledge(int $alertId): void {
        AuthMiddleware::requireAuth();
        $stmt = $this->pdo->prepare(
            "UPDATE alerts SET acknowledged = 1, acknowledged_by = ? WHERE id = ?"
        );
        $stmt->execute([$_SESSION['user_id'], $alertId]);
    }
}
