<header>
    <h1>Portfolio</h1>
    <?php
    use App\controllers\AuthController;

    $user = AuthController::connected();
    echo '<script>';
    echo 'console.log(' . $user . ');';
    echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
    echo '</script>';
    if ($user): ?>
        <p>Bonjour, <?= htmlspecialchars($user['username']) ?> !</p>
    <?php else: ?>
        <p>Bienvenue !</p>
    <?php endif; ?>
</header>