<?php
require_once __DIR__ . '/../Models/CameraModel.php';
require_once __DIR__ . '/../Models/DetectionModel.php';
require_once __DIR__ . '/AlertService.php';

class DetectionService {
    private $cameraModel;
    private $detectionModel;
    private $alertService;

    // ⚠️ Configure sua chave API aqui
    private $apiKey = 'SUA_API_KEY_ROBOFLOW';
    private $apiUrl = 'https://detect.roboflow.com/SEU_MODELO/1';

    public function __construct() {
        $this->cameraModel = new CameraModel();
        $this->detectionModel = new DetectionModel();
        $this->alertService = new AlertService();
    }

    public function processFrame(int $cameraId): array {
        $camera = $this->cameraModel->getById($cameraId);
        if (!$camera) return ['success' => false, 'error' => 'Câmera não encontrada'];

        // Capturar frame via FFmpeg
        $framePath = "/var/www/epi-monitor/public/streams/camera_{$cameraId}/frame_" . time() . ".jpg";
        shell_exec(sprintf(
            'ffmpeg -rtsp_transport tcp -i "%s" -frames:v 1 -q:v 2 %s 2>/dev/null',
            $camera['rtsp_url'], $framePath
        ));

        if (!file_exists($framePath)) return ['success' => false, 'error' => 'Falha na captura'];

        // Enviar para API de IA
        $result = $this->callAI($framePath);

        // Processar resultado
        $detected = [];
        $missing = [];
        $requiredEpis = $this->getRequiredEpis($cameraId);

        foreach ($result['predictions'] ?? [] as $pred) {
            $detected[] = $pred['class'];
        }

        foreach ($requiredEpis as $epi) {
            if (!in_array($epi, $detected)) {
                $missing[] = $epi;
            }
        }

        // Salvar detecção
        $detectionId = $this->detectionModel->create([
            'camera_id' => $cameraId,
            'frame_path' => $framePath,
            'result_json' => $result,
            'epis_detected' => $detected,
            'epis_missing' => $missing,
            'confidence' => $result['predictions'][0]['confidence'] ?? 0
        ]);

        // Gerar alerta se EPIs faltando
        if (!empty($missing)) {
            $this->alertService->create($detectionId, $cameraId, $missing);
        }

        return ['success' => true, 'detected' => $detected, 'missing' => $missing];
    }

    private function callAI(string $imagePath): array {
        $imageData = base64_encode(file_get_contents($imagePath));
        $ch = curl_init($this->apiUrl . '?api_key=' . $this->apiKey);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $imageData,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true) ?? [];
    }

    private function getRequiredEpis(int $cameraId): array {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            "SELECT e.name FROM camera_epi_config c
             JOIN epi_types e ON c.epi_type_id = e.id
             WHERE c.camera_id = ? AND c.required = 1"
        );
        $stmt->execute([$cameraId]);
        return array_column($stmt->fetchAll(), 'name');
    }
}
