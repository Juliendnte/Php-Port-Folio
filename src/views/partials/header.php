<header>
    <h1>Portfolio</h1>
    <?php
    use App\controllers\AuthController;

    $user = AuthController::connected();
    if ($user): ?>
        <p>Bonjour, <?= htmlspecialchars($user['username']) ?> !</p>
    <?php else: ?>
        <p>Bienvenue !</p>
    <?php endif; ?>
</header>