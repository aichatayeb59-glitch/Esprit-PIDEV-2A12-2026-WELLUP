<?php
require_once __DIR__ . '/../../controllers/account.php';
require_once __DIR__ . '/layout.php';

frontHeader('WELLUP - Mon Profil', 'profile');
?>
<main class="container">
    <div class="profile-box">
        <h2>Informations du Profil</h2>
        <div class="profile-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($currentUser['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($currentUser['prenom']) ?></p>
            <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($currentUser['username']) ?></p>
            <p><strong>Adresse Email :</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
            <p><strong>Rôle :</strong> <?= htmlspecialchars($currentUser['role']) ?></p>
            <p><strong>Membre depuis :</strong> <?= htmlspecialchars($currentUser['created_at'] ?? 'N/A') ?></p>
        </div>

        <div class="actions">
            <a href="edit_profile.php" class="btn">Modifier le profil</a>
            <a href="change_password.php" class="btn btn-secondary">Changer le mot de passe</a>
        </div>
    </div>
</main>
<?php frontFooter(); ?>
