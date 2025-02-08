<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Php-Port-Folio' ?></title>
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>

<main>
    <?= $content ?? '' ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html><?php
