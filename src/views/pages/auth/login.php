<form method="post" action="/login">
    <label for="email"></label>
    <input type="email" name="email" id="email" required
           value="<?php echo isset($_SESSION['log_email']) ? htmlspecialchars($_SESSION['log_email']) : '' ?>">

    <?php
    if (isset($_SESSION["errors"]["email"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["email"]);
        unset($_SESSION["errors"]["email"]);
    }
    ?>
    <label for="password"></label>
    <input type="password" name="password" id="password" required
    value="<?php echo isset($_SESSION['log_password']) ? htmlspecialchars($_SESSION['log_password']) : '' ?>">
    <?php
    if (isset($_SESSION["errors"]) && count($_SESSION["errors"]) > 0) {
        if (isset($_SESSION["errors"]["password"])) {
            echo($_SESSION["errors"]["password"]);
            unset($_SESSION["errors"]["password"]);
        }
        if (isset($_SESSION["errors"]["identifiant"])) {
            echo($_SESSION["errors"]["identifiant"]);
            unset($_SESSION["errors"]["identifiant"]);
        }
    }
    ?>

    <label>
        <input type="checkbox" name="remember"> Se souvenir de moi
    </label>

    <button type="submit">Se connecter</button>
</form>
