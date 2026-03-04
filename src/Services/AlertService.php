<?php
require_once __DIR__ . '/../../config/database.php';

class AlertService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create(int $detectionId, int $cameraId, array $missingEpis): int {
        $msg = 'EPIs não detectados: ' . implode(', ', $missingEpis);

        $stmt = $this->pdo->prepare(
            "INSERT INTO alerts (detection_id, camera_id, type, severity, message) VALUES (?, ?, 'missing_epi', 'high', ?)"
        );
        $stmt->execute([$detectionId, $cameraId, $msg]);
        $alertId = (int) $this->pdo->lastInsertId();

        // Registrar ocorrência (RF07)
        $stmt2 = $this->pdo->prepare(
            "INSERT INTO occurrences (alert_id, camera_id, description) VALUES (?, ?, ?)"
        );
        $stmt2->execute([$alertId, $cameraId, $msg]);

        return $alertId;
    }
}
