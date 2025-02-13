<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';
?>

<h2> DASHBOARD </h2>
<p>Créer un nouveau skill</p>
<form action="/skill/add" method="post">
    <label for="name">Nom du skill</label>
    <input type="text" name="name" id="name">
    <input type="submit" value="Ajouter">
</form>
<br>
<p>Skills :</p>
<?php
foreach ($skills as $skill) {
    echo '- '.$skill['name'] . '   <a href="/skill/delete/'.$skill['id'].'">(Supprimer ce skill)</a><br>';
}
?>