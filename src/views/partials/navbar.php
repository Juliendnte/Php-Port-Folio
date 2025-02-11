<nav>
    <ul>
        <li><a href="/">Accueil</a></li>
        <?php

        use App\controllers\AuthController;
        use App\controllers\UserController;

        $user = AuthController::connected();
        if (!empty($user)): ?>
            <li><a href="/profile">Profile</a></li>
            <?php if (UserController::isAdmin($user['id_role'])): ?>
                <li><a href="/dashboard">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>