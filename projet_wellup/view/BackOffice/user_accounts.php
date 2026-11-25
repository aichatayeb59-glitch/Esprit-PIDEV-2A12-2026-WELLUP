<?php
require_once __DIR__ . '/../../controllers/account.php';

if (($currentUser['role'] ?? 'user') !== 'admin') {
    header('Location: ../FrontOffice/user_space.php');
    exit;
}

$users = $userModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELLUP - BackOffice Comptes</title>
    <link rel="stylesheet" href="../../css/wellup.css">
</head>
<body>
    <header class="navbar admin-nav">
        <div class="logo">WELLUP • BackOffice</div>
        <nav>
            <a href="user_accounts.php" class="active">Comptes Utilisateurs</a>
            <a href="../FrontOffice/user_space.php">Voir FrontOffice</a>
            <a href="../FrontOffice/logout.php" class="btn-logout">Déconnexion</a>
        </nav>
    </header>

    <main class="container">
        <div class="table-box">
            <h2>Gestion des comptes utilisateurs</h2>
            <p>Consultation des profils enregistrés sur la plateforme de suivi d'activité physique.</p>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom & Prénom</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['role']) ?></td>
                            <td><?= htmlspecialchars($u['created_at'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
