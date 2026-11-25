<?php
require_once __DIR__ . '/../../controllers/account.php';
require_once __DIR__ . '/layout.php';

frontHeader('WELLUP - Changer le mot de passe', 'profile');
?>
<main class="container">
    <div class="form-box">
        <h2>Changer le mot de passe</h2>

        <?php if ($flashMessage): ?>
            <div class="alert <?= $flashType ?>"><?= htmlspecialchars($flashMessage) ?></div>
        <?php endif; ?>

        <form id="changePasswordForm" method="POST" action="change_password.php" novalidate>
            <input type="hidden" name="action" value="change_password">

            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Nouveau mot de passe (min. 8 caractères)</label>
                <input type="password" id="new_password" name="new_password" required>
                <small class="error-msg" id="newPwdError"></small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <small class="error-msg" id="confirmPwdError"></small>
            </div>

            <div class="actions">
                <button type="submit" class="btn">Mettre à jour le mot de passe</button>
                <a href="profile.php" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
</main>
<script src="../../js/profile.js"></script>
<?php frontFooter(); ?>
