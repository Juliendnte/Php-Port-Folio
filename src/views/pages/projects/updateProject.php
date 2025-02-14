<h2>Modifier Votre Projet</h2>
<form action="/projects/update/<?= $variables['project']['id'] ?>" method="post" enctype="multipart/form-data">
    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" value="<?= $variables['project']['title'] ?>">

    <label for="description">Description :</label>
    <input type="text" name="description" id="description" value="<?= $variables['project']['description'] ?>">

    <label for="link">Link :</label>
    <input type="text" name="link" id="link" value="<?= $variables['project']['link'] ?>">

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
    <input type="submit" value="Modifier">
</form>