<?php
/**
 * views/admin_space.php
 * Backoffice dashboard - visually distinct from the frontoffice.
 * Expects: $user (UserModel, the admin), $allUsers (UserModel[]), $totalUsers (int),
 *          $totalAdmins (int), $totalRegular (int), $csrfToken (string), $flash (?array)
 */
ob_start();
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Comptes au total</div>
        <div class="value"><?= $totalUsers ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Utilisateurs</div>
        <div class="value accent"><?= $totalRegular ?></div>
    </div>
    <div class="stat-card">
        <div class="label">Administrateurs</div>
        <div class="value red"><?= $totalAdmins ?></div>
    </div>
</div>

<div class="card">
    <h2>Tous les utilisateurs</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Username</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($allUsers as $u): /** @var UserModel $u */ ?>
            <tr>
                <td><?= $u->getId() ?></td>
                <td><?= htmlspecialchars($u->getUsername(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($u->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <span class="badge <?= $u->getRole() === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                        <?= htmlspecialchars($u->getRole(), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($u->getCreatedAt(), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="actions-cell">
                    <a class="btn-sm" href="index.php?action=admin_edit_user&amp;id=<?= $u->getId() ?>">Modifier</a>
                    <?php if ($u->getId() !== $user->getId()): ?>
                        <form method="POST" action="index.php?action=admin_delete_user" class="inline-form"
                              data-confirm="Supprimer définitivement ce compte ?">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= $u->getId() ?>">
                            <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard';
$activePage = 'dashboard';
require __DIR__ . '/layout_admin.php';
