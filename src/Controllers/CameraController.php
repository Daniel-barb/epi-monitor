<?php
require_once __DIR__ . '/../Models/CameraModel.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class CameraController {
    private $model;

    public function __construct() {
        $this->model = new CameraModel();
    }

    public function index(): array {
        AuthMiddleware::requireAuth();
        return $this->model->getAll();
    }

    public function store(array $data): int {
        AuthMiddleware::requireRole('admin');
        return $this->model->create($data);
    }

    public function update(int $id, array $data): void {
        AuthMiddleware::requireRole('admin');
        $this->model->update($id, $data);
    }

    public function destroy(int $id): void {
        AuthMiddleware::requireRole('admin');
        $this->model->delete($id);
    }
}
