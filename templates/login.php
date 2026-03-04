<div class="login-box">
    <h1>🛡️ EPI Monitor</h1>
    <?php if (isset($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>
