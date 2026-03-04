function startStream(cameraId) {
    fetch(`/index.php?page=api&action=start_stream&camera_id=${cameraId}`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const video = document.getElementById(`video-${cameraId}`);
                if (Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(data.url);
                    hls.attachMedia(video);
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = data.url;
                }
            }
        });
}

function stopStream(cameraId) {
    fetch(`/index.php?page=api&action=stop_stream&camera_id=${cameraId}`);
    document.getElementById(`video-${cameraId}`).src = '';
}

function acknowledgeAlert(alertId) {
    fetch('/index.php?page=api&action=acknowledge_alert', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `alert_id=${alertId}`
    }).then(() => location.reload());
}
