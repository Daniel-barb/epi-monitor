<h2>Dashboard</h2>
<div class="grid">
    <div class="card">
        <h3>📷 Câmeras Ativas</h3>
        <span class="big-number"><?= count(array_filter($data['cameras'], fn($c) => $c['status'] === 'active')) ?></span>
    </div>
    <div class="card">
        <h3>⚠️ Alertas Pendentes</h3>
        <span class="big-number"><?= count(array_filter($data['alerts'], fn($a) => !$a['acknowledged'])) ?></span>
    </div>
</div>
<h3>Últimos Alertas</h3>
<table>
    <tr><th>Câmera</th><th>Mensagem</th><th>Data</th><th>Status</th></tr>
    <?php foreach ($data['alerts'] as $alert): ?>
    <tr>
        <td><?= htmlspecialchars($alert['camera_name']) ?></td>
        <td><?= htmlspecialchars($alert['message']) ?></td>
        <td><?= $alert['created_at'] ?></td>
        <td><?= $alert['acknowledged'] ? '✅' : '🔴' ?></td>
    </tr>
    <?php endforeach; ?>
</table>
