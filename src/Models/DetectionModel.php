<?php
require_once __DIR__ . '/../../config/database.php';

class DetectionModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO detections (camera_id, frame_path, result_json, epis_detected, epis_missing, confidence)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['camera_id'], $data['frame_path'],
            json_encode($data['result_json']),
            json_encode($data['epis_detected']),
            json_encode($data['epis_missing']),
            $data['confidence']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function getByCamera(int $cameraId, int $limit = 50): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM detections WHERE camera_id = ? ORDER BY detected_at DESC LIMIT ?"
        );
        $stmt->execute([$cameraId, $limit]);
        return $stmt->fetchAll();
    }
}
