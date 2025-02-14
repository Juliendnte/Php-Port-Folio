<?php

use App\controllers\AuthController;

if (AuthController::connected()) {
    header("Location:index");
}
?>

    <form method="post" action="/register">
        <label for="username">Username :</label><input type="text" name="username" id="username" required
                                                       value="<?php echo isset($_SESSION['log_username']) ? htmlspecialchars($_SESSION['log_username']) : '' ?>">

        <br>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required
               value="<?php echo isset($_SESSION['log_email']) ? htmlspecialchars($_SESSION['log_email']) : '' ?>">

        <?php

        if (isset($_SESSION["errors"]["email"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["email"]);
            unset($_SESSION["errors"]["email"]);
        }
        ?>
        <br>
        <label for="password">Password :</label>
        <input type="password" name="password" id="password" required
               value="<?php echo isset($_SESSION['log_password']) ? htmlspecialchars($_SESSION['log_password']) : '' ?>">
        <?php

        if (isset($_SESSION["errors"]["password"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["password"]);
            unset($_SESSION["errors"]["password"]);
        }
        ?>
        <br>
        <label for="confirm_password">Password Confirm :</label><input type="password" id="confirm_password"
                                                                       name="confirm_password" required>
        value="<?php echo isset($_SESSION['log_confirm_password']) ? htmlspecialchars($_SESSION['log_confirm_password']) : '' ?>
        ">

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
<?php
unset($_SESSION["log_email"]);
unset($_SESSION["log_password"]);
unset($_SESSION["log_confirm_password"]);
unset($_SESSION["log_username"]);
?>