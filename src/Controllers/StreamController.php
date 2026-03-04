<?php
require_once __DIR__ . '/../Models/CameraModel.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class StreamController {
    private $cameraModel;
    private $streamDir = '/var/www/epi-monitor/public/streams/';

    public function __construct() {
        $this->cameraModel = new CameraModel();
    }

    public function startStream(int $cameraId): array {
        AuthMiddleware::requireAuth();
        $camera = $this->cameraModel->getById($cameraId);
        if (!$camera) return ['success' => false, 'error' => 'Câmera não encontrada'];

        $outputDir = $this->streamDir . "camera_{$cameraId}/";
        if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

        // Verificar se já está rodando
        $pid = shell_exec("pgrep -f 'ffmpeg.*camera_{$cameraId}'");
        if (trim($pid)) return ['success' => true, 'message' => 'Stream já ativo', 'url' => "/streams/camera_{$cameraId}/index.m3u8"];

        $cmd = sprintf(
            'nohup ffmpeg -rtsp_transport tcp -i "%s" '
            . '-c:v libx264 -preset ultrafast -tune zerolatency '
            . '-f hls -hls_time 2 -hls_list_size 5 -hls_flags delete_segments '
            . '%sindex.m3u8 > /dev/null 2>&1 &',
            escapeshellarg($camera['rtsp_url']),
            $outputDir
        );
        shell_exec($cmd);

        return ['success' => true, 'url' => "/streams/camera_{$cameraId}/index.m3u8"];
    }

    public function stopStream(int $cameraId): void {
        AuthMiddleware::requireAuth();
        shell_exec("pkill -f 'ffmpeg.*camera_{$cameraId}'");
    }
}
