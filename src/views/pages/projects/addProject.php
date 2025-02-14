<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';
?>
    <h2>Ajouter Votre Projet</h2>
    <form action="/projects/add" method="post" enctype="multipart/form-data">
        <label for="title">Titre :</label>
        <input type="text" name="title" id="title" required
               value="<?php echo isset($_SESSION['project_title']) ? htmlspecialchars($_SESSION['project_title']) : '' ?>">
        <?php
        if (isset($_SESSION["errors"]["title"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["title"]);
            unset($_SESSION["errors"]["title"]);
        }
        ?>
        <br>
        <label for="description">Description :</label>
        <input type="text" name="description" id="description" required
               value="<?php echo isset($_SESSION['project_description']) ? htmlspecialchars($_SESSION['project_description']) : '' ?>">
        <?php
        if (isset($_SESSION["errors"]["description"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["description"]);
            unset($_SESSION["errors"]["description"]);
        }
        ?>
        <br>
        <label for="link">Link :</label>
        <input type="text" name="link" id="link"
               value="<?php echo isset($_SESSION['project_link']) ? htmlspecialchars($_SESSION['project_link']) : '' ?>">

        <br>
        <label for="image">Image :</label>
        <input type="file" name="image" id="image">
        <?php
        if (isset($_SESSION["errors"]["image"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["image"]);
            unset($_SESSION["errors"]["image"]);
        }
        if (isset($_SESSION["errors"]["BDD"]) && count($_SESSION["errors"]) > 0) {
            echo($_SESSION["errors"]["BDD"]);
            unset($_SESSION["errors"]["BDD"]);
        }
        ?>
        <input type="submit" value="Ajouter">
    </form>

<?php
unset($_SESSION['project_title']);
unset($_SESSION['project_description']);
unset($_SESSION['project_link']);
?>