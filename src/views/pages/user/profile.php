<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';
$hasProjects = !empty($variables['project']);
?>
    <h2>Mon Profil</h2>
    <p>Bienvenue dans votre profil, <?= htmlspecialchars($variables['user']['username']) ?> !</p>
    <br/>
    <a href="/profile/update">Modification de ton profile</a>
    <br/>

    <a href="/projects/add">Créer <?php echo $hasProjects ? 'un' : 'ton premier' ?> post</a>
<br/>
<?php
if (!$hasProjects) {
    echo '<p>Vous n\'avez pas encore de post.</p>';
} else {
    echo '<p>Voici les derniers posts :</p>';
    foreach ($variables['project'] as $project) {
        if(isset($project['link'])){
            echo '<a href="' . htmlspecialchars($project['link']) . '">' . htmlspecialchars($project['title']) . '</a>';
        }else {
            echo '<p>' . htmlspecialchars($project['title']) . '</p>';
        }
        echo '<p>' . htmlspecialchars($project['description']) . '</p>';
        echo '<img width="50px" heigth="50px" src="'.$_ENV['BASE_URL'].'/images/'. htmlspecialchars($project['image']).'" alt="img" />';
        echo '<br/>';
    }
}
?>