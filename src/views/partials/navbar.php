<nav>
    <ul>
        <li><a href="/">Accueil</a></li>
        <?php

        use App\controllers\AuthController;

        if (AuthController::connected()): ?>
            <li><a href="/profile">Profile</a></li>
            <li><a href="/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>