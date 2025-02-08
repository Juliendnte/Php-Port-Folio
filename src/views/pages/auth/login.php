<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';
?>

<form method="post" action="/login">
    <label for="email"></label>
    <input type="email" name="email" id="email" required>
    <?php
    if (isset($_SESSION["errors"]["email"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["email"]);
        unset($_SESSION["errors"]["email"]);
    }
    ?>
    <label for="password"></label>
    <input type="password" name="password" id="password" required>
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
    <button type="submit">Se connecter</button>
</form>
