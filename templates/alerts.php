<h2>Central de Alertas</h2>
<table>
    <tr><th>Câmera</th><th>Tipo</th><th>Mensagem</th><th>Severidade</th><th>Data</th><th>Ação</th></tr>
    <?php foreach ($data['alerts'] as $a): ?>
    <tr class="severity-<?= $a['severity'] ?>">
        <td><?= htmlspecialchars($a['camera_name']) ?></td>
        <td><?= $a['type'] ?></td>
        <td><?= htmlspecialchars($a['message']) ?></td>
        <td><?= $a['severity'] ?></td>
        <td><?= $a['created_at'] ?></td>
        <td>
            <?php if (!$a['acknowledged']): ?>
                <button onclick="acknowledgeAlert(<?= $a['id'] ?>)">✅ Confirmar</button>
            <?php else: ?>
                Confirmado
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
