<?php
/**
 * views/layout.php
 * Global HTML layout for the FRONTOFFICE (WELLUP template)
 * Expects: $title (string), $content (string, already generated/escaped)
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'WELLUP', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/wellup.css">
</head>
<body>
<nav class="topnav">
    <a class="brand" href="index.php?action=home">
        <img class="brand-img" src="assets/images/logo.png" alt="">
        <span>WELL<b>UP</b></span>
    </a>
    <div class="links">
        <?php if (!empty($_SESSION['user_id'])): ?>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="index.php?action=admin">Administration</a>
            <?php else: ?>
                <a href="index.php?action=dashboard">Mon espace</a>
            <?php endif; ?>
            <a href="index.php?action=profile">Profil</a>
            <a href="index.php?action=account">Mon compte</a>
            <form method="POST" action="index.php?action=logout" class="logout-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit">Se déconnecter</button>
            </form>
        <?php else: ?>
            <a href="index.php?action=login">Connexion</a>
            <a href="index.php?action=register" class="btn-primary">Inscription</a>
        <?php endif; ?>
    </div>
</nav>
<?= $content ?>
<footer class="site-footer">© WELLUP — Plateforme de bien-être</footer>
<script src="assets/js/app.js" defer></script>
<script src="assets/js/validation.js" defer></script>
</body>
</html>
