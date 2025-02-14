<form action="/profile/update" method="post">
    <label for="username">Name</label>
    <input type="text" name="username" id="username">

    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <?php
    if (isset($_SESSION["errors"]["email"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["email"]);
        unset($_SESSION["errors"]["email"]);
    }
    ?>

    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="Update">
</form>