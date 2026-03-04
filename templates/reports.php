<h2>Relatórios de Irregularidades</h2>
<?php
$pdo = Database::getInstance();
$reports = $pdo->query(
    "SELECT c.name as camera, COUNT(a.id) as total,
            SUM(CASE WHEN a.severity='critical' THEN 1 ELSE 0 END) as criticos
     FROM alerts a JOIN cameras c ON a.camera_id = c.id
     GROUP BY c.id ORDER BY total DESC"
)->fetchAll();
?>
<table>
    <tr><th>Câmera</th><th>Total Alertas</th><th>Críticos</th></tr>
    <?php foreach ($reports as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r['camera']) ?></td>
        <td><?= $r['total'] ?></td>
        <td><?= $r['criticos'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
