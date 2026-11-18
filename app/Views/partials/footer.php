<!-- Footer (espace utilisateur) -->
<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> WELLUP — Plateforme de bien-être</p>
        <p class="muted">
            Rôle de test :
            <a href="<?= url('dev_role', ['role' => 'user']) ?>">Utilisateur</a> |
            <a href="<?= url('dev_role', ['role' => 'admin']) ?>">Admin</a>
        </p>
    </div>
</footer>
<!-- /Footer -->
