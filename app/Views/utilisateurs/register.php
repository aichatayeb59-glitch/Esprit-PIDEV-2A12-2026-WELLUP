<?php
/**
 * views/register.php
 * Expects: $errors (array), $old (array), $csrfToken (string)
 */
ob_start();
$old = $old ?? [];
?>
<div class="container">
    <h1>Créer un compte</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=register">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required maxlength="50"
               value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required maxlength="150" autocomplete="email"
               value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="password">Mot de passe (8 caractères minimum)</label>
        <div class="pw">
            <input type="password" id="password" name="password" required autocomplete="new-password">
            <button type="button" class="pw-toggle" data-toggle-pw="password">Afficher</button>
        </div>

        <label for="password_confirm">Confirmer le mot de passe</label>
        <div class="pw">
            <input type="password" id="password_confirm" name="password_confirm" required autocomplete="new-password">
            <button type="button" class="pw-toggle" data-toggle-pw="password_confirm">Afficher</button>
        </div>

        <button type="submit">Créer mon compte</button>
    </form>

    <div class="switch-link">
        Déjà inscrit ? <a href="index.php?action=login">Se connecter</a>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Inscription - WELLUP';
require __DIR__ . '/layout.php';
