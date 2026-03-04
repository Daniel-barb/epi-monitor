<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPI Monitor</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
<nav class="navbar">
    <div class="logo">🛡️ EPI Monitor</div>
    <ul>
        <li><a href="/index.php?page=dashboard">Dashboard</a></li>
        <li><a href="/index.php?page=cameras">Câmeras</a></li>
        <li><a href="/index.php?page=stream">Streaming</a></li>
        <li><a href="/index.php?page=alerts">Alertas</a></li>
        <li><a href="/index.php?page=reports">Relatórios</a></li>
        <li><a href="/index.php?page=logout">Sair (<?= $_SESSION['user_name'] ?>)</a></li>
    </ul>
</nav>
<main class="container">
    <?php require __DIR__ . "/{$page}.php"; ?>
</main>
<script src="/js/app.js"></script>
</body>
</html>
