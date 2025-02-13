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
    <p>Vos Skills :</p>
<?php
$skillModel = new \App\models\Skill();
foreach ($variables['skills'] as $skill) {
    $skillName = $skillModel->getSkillById($skill['skill_id']);
    echo '- ' . $skillName[0]['name'] . ' (' . htmlspecialchars($skill['level']) . ') (<a href="/profile/skill/delete/' . $skill['skill_id'] . '">Supprimer ce skill</a>)<br>';
}
?>
    <p>Ajouter un Skill</p>
    <form action="/profile/addSkill" method="post">
        <label for="skill">
            Choisissez un Skill :
        </label>
        <select required name="skill" id="skill">
            <?php foreach ($availableSkills as $skill): ?>
                <option value="<?= htmlspecialchars($skill['id']) ?>">
                    <?= htmlspecialchars($skill['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br/>
        <label for="level">
            Niveau :
        </label>

        <select required name="level" id="level">
            <?php
            $levels = ['débutant', 'intermédiare', 'expert'];
            foreach ($levels as $level): ?>
                <option value="<?= htmlspecialchars($level) ?>">
                    <?= htmlspecialchars($level) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Ajouter"/>
    </form>

    <br/>

    <a href="/projects/add">Créer <?php echo $hasProjects ? 'un' : 'ton premier' ?> post</a>
    <br/>
<?php
if (!$hasProjects) {
    echo '<p>Vous n\'avez pas encore de post.</p>';
} else {
    echo '<p>Voici les derniers posts :</p>';
    foreach ($variables['project'] as $project) {
        if (isset($project['link'])) {
            echo '<a href="' . htmlspecialchars($project['link']) . '">' . htmlspecialchars($project['title']) . '</a>';
        } else {
            echo '<p>' . htmlspecialchars($project['title']) . '</p>';
        }
        echo '<p>' . htmlspecialchars($project['description']) . '</p>';
        echo '<img width="150px" heigth="150px" src="' . $_ENV['BASE_URL'] . '/images/' . htmlspecialchars($project['image']) . '" alt="img" />';
        echo '<br/>';
    }
}
?>