<?php
require_once __DIR__ . '/../../controllers/account.php';
require_once __DIR__ . '/layout.php';

frontHeader('WELLUP - Modifier le profil', 'profile');
?>
<main class="container">
    <div class="form-box">
        <h2>Modifier mon profil</h2>

        <?php if ($flashMessage): ?>
            <div class="alert <?= $flashType ?>"><?= htmlspecialchars($flashMessage) ?></div>
        <?php endif; ?>

        <form id="editProfileForm" method="POST" action="edit_profile.php" novalidate>
            <input type="hidden" name="action" value="update_profile">

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($currentUser['nom']) ?>" required>
                <small class="error-msg" id="nomError"></small>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($currentUser['prenom']) ?>" required>
                <small class="error-msg" id="prenomError"></small>
            </div>

            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($currentUser['username']) ?>" required>
                <small class="error-msg" id="usernameError"></small>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($currentUser['email']) ?>" required>
                <small class="error-msg" id="emailError"></small>
            </div>

            <div class="actions">
                <button type="submit" class="btn">Enregistrer les modifications</button>
                <a href="profile.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</main>
<script src="../../js/profile.js"></script>
<?php frontFooter(); ?>
