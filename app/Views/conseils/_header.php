<?php
/** En-tête commun du module (à remplacer par le header du template WELLUP si disponible). */
$e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
$action = $_GET['action'] ?? 'index';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'Conseils') ?> — WELLUP</title>
    <link rel="stylesheet" href="assets/css/conseils.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar__inner">
        <a class="brand" href="index.php?action=index">WELL<span>UP</span></a>
        <nav class="nav">
            <a href="index.php?action=index" class="<?= $action === 'index' ? 'is-active' : '' ?>">Conseils</a>
            <a href="index.php?action=ia" class="<?= $action === 'ia' ? 'is-active' : '' ?>">Conseils IA</a>
            <?php if (!empty($isAdmin)): ?>
                <span class="nav__sep"></span>
                <a href="index.php?action=admin" class="<?= in_array($action, ['admin', 'ajouter', 'modifier'], true) ? 'is-active' : '' ?>">Conseils (admin)</a>
                <a href="index.php?action=astuces" class="<?= in_array($action, ['astuces', 'ajouter_astuce', 'modifier_astuce'], true) ? 'is-active' : '' ?>">Astuces (admin)</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container page">
<?php if (!empty($flash)): ?>
    <div class="alert alert--<?= $e($flash['type']) ?>" role="status"><?= $e($flash['message']) ?></div>
<?php endif; ?>
