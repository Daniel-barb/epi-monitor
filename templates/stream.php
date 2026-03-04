<h2>Streaming ao Vivo</h2>
<div id="stream-container">
    <?php foreach ($data['camera'] as $cam): ?>
    <div class="stream-box">
        <h4><?= htmlspecialchars($cam['name']) ?></h4>
        <video id="video-<?= $cam['id'] ?>" controls muted autoplay width="640"></video>
        <button onclick="startStream(<?= $cam['id'] ?>)">▶ Iniciar</button>
        <button onclick="stopStream(<?= $cam['id'] ?>)">⏹ Parar</button>
    </div>
    <?php endforeach; ?>
</div>
