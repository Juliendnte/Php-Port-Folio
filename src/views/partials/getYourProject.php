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