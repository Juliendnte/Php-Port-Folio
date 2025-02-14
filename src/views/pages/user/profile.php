<?php
echo '<script>';
echo 'console.log(' . json_encode($_SESSION, JSON_PRETTY_PRINT) . ');';
echo '</script>';

use App\models\Skill;

$hasProjects = !empty($variables['project']);
?>
<h2>Mon Profil</h2>
<p>Bienvenue dans votre profil, <?= htmlspecialchars($variables['user']['username']) ?> !</p>
<br/>
<a href="/profile/update">Modification de ton profile</a>
<br/>
<?php
if (count($variables['skills']) === 0) {
    echo 'Vous n\'avez pas encore de skill';
}else{
    echo '<p>Vos Skills :</p>';
}
?>
<?php
$skillModel = new Skill();
$levels = ['débutant', 'intermédiaire', 'expert'];

foreach ($variables['skills'] as $skill) {
    $skillName = $skillModel->getSkillById($skill['skill_id']);
    echo '- ' . $skillName[0]['name'] . ' (' . htmlspecialchars($skill['level']) . ')';
    ?>
    <form action="/profile/skill/update/<?= htmlspecialchars($skill['skill_id']) ?>" method="post">
        <label for="level_<?= htmlspecialchars($skill['skill_id']) ?>">Niveau :</label>
        <?php include __DIR__ . '/../../partials/selectLevelSkill.php'; ?>
        <button type="submit">Mettre à jour</button>
    </form>
    <a href="/profile/skill/delete/<?= htmlspecialchars($skill['skill_id']) ?>">Supprimer ce skill</a>
    <?php
    if (isset($_SESSION["errors"]["id_del_project"]) && count($_SESSION["errors"]) > 0) {
        echo($_SESSION["errors"]["id_del_project"]);
        unset($_SESSION["errors"]["id_del_project"]);
    }
    ?>
    <br>
    <br>
    <?php
}
?>
<p>Ajouter un Skill</p>
<?php
if (isset($_SESSION["errors"]["duplicata"]) && count($_SESSION["errors"]) > 0) {
    echo($_SESSION["errors"]["duplicata"]);
    unset($_SESSION["errors"]["duplicata"]);
}
?>
<form action="/profile/addSkill" method="post">
    <label for="skill">
        Choisissez un Skill :
    </label>
    <?php include __DIR__ . '/../../partials/selectSkill.php'; ?>
    <br/>
    <label for="level">
        Niveau :
    </label>

    <select required name="level" id="level">
        <?php
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
<?php include __DIR__ . '/../../partials/getYourProject.php'; ?>
