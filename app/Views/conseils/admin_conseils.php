<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head page-head--row">
    <div>
        <h1>Gestion des conseils</h1>
        <p>Ajoutez, modifiez ou supprimez les conseils de bien-être.</p>
    </div>
    <a href="index.php?action=ajouter" class="btn btn--primary">+ Ajouter un conseil</a>
</div>

<?php if (empty($conseils)): ?>
    <div class="empty">
        <h2>Aucun conseil enregistré</h2>
        <p>Commencez par ajouter votre premier conseil.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Titre</th><th>Catégorie</th><th>Date</th><th class="actions">Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($conseils as $c): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><?= $e($c['titre']) ?></td>
                    <td><span class="badge"><?= $e($c['categorie']) ?></span></td>
                    <td><?= !empty($c['date_creation']) ? $e(date('d/m/Y', strtotime($c['date_creation']))) : '—' ?></td>
                    <td class="actions">
                        <a class="btn btn--ghost btn--sm" href="index.php?action=modifier&amp;id=<?= (int)$c['id'] ?>">Modifier</a>
                        <form method="post" action="index.php?action=supprimer" class="inline"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce conseil ? Cette action est irréversible.');">
                            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                            <button type="submit" class="btn btn--danger btn--sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/_footer.php'; ?>
