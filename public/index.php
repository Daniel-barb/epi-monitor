<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../src/Models/UserModel.php';
require_once __DIR__ . '/../src/Models/CameraModel.php';
require_once __DIR__ . '/../src/Models/DetectionModel.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/CameraController.php';
require_once __DIR__ . '/../src/Controllers/AlertController.php';
require_once __DIR__ . '/../src/Controllers/StreamController.php';
require_once __DIR__ . '/../src/Services/DetectionService.php';
require_once __DIR__ . '/../src/Services/AlertService.php';

$page = $_GET['page'] ?? 'dashboard';

// Login
if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth = new AuthController();
        if ($auth->login($_POST['email'], $_POST['password'])) {
            header('Location: /index.php?page=dashboard');
            exit;
        }
        $error = 'Credenciais inválidas';
    }
    require __DIR__ . '/../templates/login.php';
    exit;
}

if ($page === 'logout') {
    (new AuthController())->logout();
}

// Proteger todas as páginas
AuthMiddleware::requireAuth();

// API endpoints (AJAX)
if ($page === 'api') {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'start_stream':
            $stream = new StreamController();
            echo json_encode($stream->startStream((int)$_GET['camera_id']));
            break;
        case 'stop_stream':
            $stream = new StreamController();
            $stream->stopStream((int)$_GET['camera_id']);
            echo json_encode(['success' => true]);
            break;
        case 'acknowledge_alert':
            $alerts = new AlertController();
            $alerts->acknowledge((int)$_POST['alert_id']);
            echo json_encode(['success' => true]);
            break;
    }
    exit;
}

// Carregar dados para a página
$data = [];
switch ($page) {
    case 'dashboard':
        $camCtrl = new CameraController();
        $alertCtrl = new AlertController();
        $data['cameras'] = $camCtrl->index();
        $data['alerts'] = $alertCtrl->getAll(10);
        break;
    case 'cameras':
        $camCtrl = new CameraController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $camCtrl->store($_POST);
            header('Location: /index.php?page=cameras');
            exit;
        }
        $data['cameras'] = $camCtrl->index();
        break;
    case 'stream':
        $camCtrl = new CameraController();
        $data['camera'] = $camCtrl->index();
        break;
    case 'alerts':
        $alertCtrl = new AlertController();
        $data['alerts'] = $alertCtrl->getAll();
        break;
}

require __DIR__ . '/../templates/layout.php';
