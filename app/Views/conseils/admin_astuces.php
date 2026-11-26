<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head page-head--row">
    <div>
        <h1>Gestion des astuces</h1>
        <p>Les astuces pratiques sont rattachées à un conseil.</p>
    </div>
    <a href="index.php?action=ajouter_astuce" class="btn btn--primary">+ Ajouter une astuce</a>
</div>

<?php if (empty($astuces)): ?>
    <div class="empty">
        <h2>Aucune astuce enregistrée</h2>
        <p>Ajoutez une première astuce pour l'un de vos conseils.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Astuce</th><th>Conseil lié</th><th>Durée</th><th class="actions">Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($astuces as $a): ?>
                <tr>
                    <td><?= (int)$a['id'] ?></td>
                    <td><?= $e($a['titre']) ?></td>
                    <td><span class="badge"><?= $e($a['conseil_titre']) ?></span></td>
                    <td><?= !empty($a['duree_minutes']) ? (int)$a['duree_minutes'] . ' min' : '—' ?></td>
                    <td class="actions">
                        <a class="btn btn--ghost btn--sm" href="index.php?action=modifier_astuce&amp;id=<?= (int)$a['id'] ?>">Modifier</a>
                        <form method="post" action="index.php?action=supprimer_astuce" class="inline"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette astuce ? Cette action est irréversible.');">
                            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
                            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
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
