<?php
require_once __DIR__ . '/../../config/database.php';

class CameraModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll(): array {
        return $this->pdo->query("SELECT * FROM cameras ORDER BY created_at DESC")->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM cameras WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO cameras (name, location, sector, rtsp_url, status) VALUES (?, ?, ?, ?, 'active')"
        );
        $stmt->execute([$data['name'], $data['location'], $data['sector'], $data['rtsp_url']]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            "UPDATE cameras SET name=?, location=?, sector=?, rtsp_url=?, status=? WHERE id=?"
        );
        $stmt->execute([$data['name'], $data['location'], $data['sector'], $data['rtsp_url'], $data['status'], $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM cameras WHERE id = ?");
        $stmt->execute([$id]);
    }
}
