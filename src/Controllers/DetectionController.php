<?php
require_once __DIR__ . '/../Services/DetectionService.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class DetectionController {
    private $service;

    public function __construct() {
        $this->service = new DetectionService();
    }

    public function analyzeFrame(int $cameraId): array {
        AuthMiddleware::requireAuth();
        return $this->service->processFrame($cameraId);
    }
}
