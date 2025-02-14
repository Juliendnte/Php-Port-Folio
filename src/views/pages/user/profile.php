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
$levels = ['débutant', 'intermédiaire', 'expert'];

foreach ($variables['skills'] as $skill) {
    $skillName = $skillModel->getSkillById($skill['skill_id']);
    echo '- ' . $skillName[0]['name'] . ' (' . htmlspecialchars($skill['level']) . ')';
    ?>
    <form action="/profile/skill/update/<?= htmlspecialchars($skill['skill_id']) ?>" method="post">
        <label for="level_<?= htmlspecialchars($skill['skill_id']) ?>">Niveau :</label>
        <select name="level" id="level_<?= htmlspecialchars($skill['skill_id']) ?>">
            <?php foreach ($levels as $level): ?>
                <option value="<?= htmlspecialchars($level) ?>" <?= $level === $skill['level'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($level) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Mettre à jour</button>
    </form>
    <a href="/profile/skill/delete/<?= htmlspecialchars($skill['skill_id']) ?>">Supprimer ce skill</a>
    <br>
    <br>
    <?php
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
<?php if (!$hasProjects): ?>
    <p>Vous n'avez pas encore de post.</p>
<?php else: ?>
    <p>Voici les derniers posts :</p>
    <?php foreach ($variables['project'] as $project): ?>
        <?php if (!empty($project['link'])): ?>
            <a href="<?= htmlspecialchars($project['link']); ?>">
                <?= htmlspecialchars($project['title']); ?>
            </a>
        <?php else: ?>
            <p><?= htmlspecialchars($project['title']); ?></p>
        <?php endif; ?>
        <p><?= htmlspecialchars($project['description']); ?></p>
        <img width="150px" height="150px"
             src="<?= $_ENV['BASE_URL'] . '/images/' . htmlspecialchars($project['image']); ?>" alt="img"/>
        <br/>
        <a href="/projects/update/<?= htmlspecialchars($project['id']) ?>">Modifier</a>
        <br/>
        <a href="/projects/delete/<?= htmlspecialchars($project['id']) ?>">Supprimer</a>
        <br>
        <br>
    <?php endforeach; ?>
<?php endif; ?>