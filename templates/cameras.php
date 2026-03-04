<h2>Gerenciar Câmeras</h2>
<form method="POST" class="form-inline">
    <input name="name" placeholder="Nome" required>
    <input name="location" placeholder="Localização">
    <input name="sector" placeholder="Setor">
    <input name="rtsp_url" placeholder="rtsp://..." required>
    <button type="submit">Adicionar</button>
</form>
<table>
    <tr><th>Nome</th><th>Local</th><th>Setor</th><th>Status</th><th>Ações</th></tr>
    <?php foreach ($data['cameras'] as $cam): ?>
    <tr>
        <td><?= htmlspecialchars($cam['name']) ?></td>
        <td><?= htmlspecialchars($cam['location']) ?></td>
        <td><?= htmlspecialchars($cam['sector']) ?></td>
        <td><?= $cam['status'] ?></td>
        <td>
            <a href="/index.php?page=stream&id=<?= $cam['id'] ?>">▶ Stream</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
