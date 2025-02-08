<?php
use App\controllers\AuthController;

if (AuthController::connected()) {
    header("Location:index");
}
?>

<form method="post" action="/register">
    <label for="username"></label><input type="text" name="username" id="username" required>
    <label for="email"></label><input type="email" name="email" id="email" required>
    <?php

    if (isset($_SESSION["errors"]["email"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["email"]);
        unset($_SESSION["errors"]["email"]);
    }
    ?>
    <label for="password"></label><input type="password" id="password" name="password" required>
    <?php

    if (isset($_SESSION["errors"]["password"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["password"]);
        unset($_SESSION["errors"]["password"]);
    }
    ?>
    <label for="confirm_password"></label><input type="password" id="confirm_password" name="confirm_password"
                                                 required>
    <?php
    if (isset($_SESSION["errors"]) && count($_SESSION["errors"]) > 0) {
        if (isset($_SESSION["errors"]["confirm_password"])) {
            echo($_SESSION["errors"]["confirm_password"]);
            unset($_SESSION["errors"]["confirm_password"]);
        }
        if (isset($_SESSION["errors"]["BDD"]) && count($_SESSION["errors"]) === 1) {
            if (str_contains($_SESSION["errors"]["BDD"], "Duplicata")) {
                echo "Cet email est déjà utilisé";
            }
            unset($_SESSION["errors"]["BDD"]);
        }
    }
    ?>
    <button type="submit">Go!!!!!!!!!!!!!</button>
</form>