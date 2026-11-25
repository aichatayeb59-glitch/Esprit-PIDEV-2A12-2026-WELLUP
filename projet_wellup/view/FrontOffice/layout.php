<?php
function frontHeader(string $title = 'WELLUP', string $activePage = ''): void {
    $isLoggedIn = isset($_SESSION['user_id']);
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="../../css/wellup.css">
</head>
<body>
    <header class="navbar">
        <a href="index.php" class="logo">WELLUP • Suivi Physique</a>
        <nav>
            <a href="index.php" class="<?= $activePage === 'home' ? 'active' : '' ?>">Accueil</a>
            <?php if ($isLoggedIn): ?>
                <a href="user_space.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
                <a href="profile.php" class="<?= $activePage === 'profile' ? 'active' : '' ?>">Mon Profil</a>
                <a href="logout.php" class="btn-logout">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="<?= $activePage === 'login' ? 'active' : '' ?>">Connexion</a>
                <a href="register.php" class="<?= $activePage === 'register' ? 'active' : '' ?>">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
    <?php
}

function frontFooter(): void {
    ?>
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> WELLUP - Système : Suivi de l'activité physique</p>
    </footer>
</body>
</html>
    <?php
}
