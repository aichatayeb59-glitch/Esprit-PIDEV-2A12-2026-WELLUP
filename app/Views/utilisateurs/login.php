<?php
/**
 * views/login.php  (Partie 3 : connexion)
 * Expects: $errors (array), $csrfToken (string), $old (array: email déjà saisi)
 */
ob_start();
$old = $old ?? ['email' => ''];
?>
<div class="container">
    <h1>Connexion</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['registered'])): ?>
        <div class="alert alert-success">Compte créé avec succès, vous pouvez maintenant vous connecter.</div>
    <?php endif; ?>

    <?php if (!empty($_GET['expired'])): ?>
        <div class="alert alert-error" role="alert">Votre session a expiré après une période d'inactivité. Veuillez vous reconnecter.</div>
    <?php endif; ?>

    <?php if (!empty($_GET['logged_out'])): ?>
        <div class="alert alert-success">Vous avez été déconnecté avec succès.</div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=login">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required maxlength="150" autofocus
               autocomplete="email" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="password">Mot de passe</label>
        <div class="pw">
            <input type="password" id="password" name="password" required autocomplete="current-password">
            <button type="button" class="pw-toggle" data-toggle-pw="password">Afficher</button>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <div class="switch-link">
        Pas encore de compte ? <a href="index.php?action=register">S'inscrire</a>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Connexion - WELLUP';
require __DIR__ . '/layout.php';
