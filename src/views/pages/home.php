<h2>Bienvenue sur la page d'accueil</h2>
<p>Ceci est la page principale de mon site web.</p>
<?php
if ($users) {
    foreach ($users as $user) {
        echo "Utilisateur : " . htmlspecialchars($user['username']) . "<br>";
    }
} else {
    echo "Aucun utilisateur trouvé.";
}

?>