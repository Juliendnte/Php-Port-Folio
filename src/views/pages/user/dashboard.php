<h2> DASHBOARD </h2>
<p>Créer un nouveau skill</p>
<form action="/skill/add" method="post">
    <label for="name">Nom du skill</label>
    <input type="text" name="name" id="name">
    <input type="submit" value="Ajouter">
</form>
<?php
if (isset($_SESSION["errors"]["duplicata"]) && count($_SESSION["errors"]) > 0) {
    echo($_SESSION["errors"]["duplicata"]);
    unset($_SESSION["errors"]["duplicata"]);
}
?>
<br>
<p>Skills :</p>
<?php
foreach ($skills as $skill) {
    echo '- '.$skill['name'] . '   <a href="/skill/delete/'.$skill['id'].'">(Supprimer ce skill)</a><br>';
}
?>