<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';
?>
<h2>Ajouter Votre Projet</h2>
<form action="/projects/add" method="post" enctype="multipart/form-data">
    <label for="title">Titre :</label>
    <input type="text" name="title" id="title" required>

    <label for="description">Description :</label>
    <input type="text" name="description" id="description" required>

    <label for="link">Link :</label>
    <input type="text" name="link" id="link">

    <label for="image">Image :</label>
    <input type="file" name="image" id="image">
    <input type="submit" value="Ajouter">
</form>