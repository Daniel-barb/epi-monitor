<?php
// Rode: crontab -e
// Adicione: */1 * * * * php /var/www/epi-monitor/cron/process_frames.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Services/DetectionService.php';

$pdo = Database::getInstance();
$cameras = $pdo->query("SELECT id FROM cameras WHERE status = 'active'")->fetchAll();
$service = new DetectionService();

foreach ($cameras as $cam) {
    $result = $service->processFrame($cam['id']);
    echo "Camera {$cam['id']}: " . ($result['success'] ? 'OK' : $result['error']) . "\n";
}
