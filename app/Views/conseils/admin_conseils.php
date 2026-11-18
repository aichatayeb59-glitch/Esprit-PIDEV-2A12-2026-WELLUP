<section class="page-head head-row">
    <div>
        <h1>Gestion des conseils</h1>
        <p class="muted">Ajoutez, modifiez ou supprimez les conseils de bien-être.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('ajouter_conseil') ?>">+ Ajouter un conseil</a>
</section>

<?php if (empty($conseils)): ?>
    <div class="empty">
        <h3>Aucun conseil enregistré</h3>
        <p class="muted">Cliquez sur « Ajouter un conseil » pour commencer.</p>
    </div>
<?php else: ?>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>#</th><th>Titre</th><th>Catégorie</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($conseils as $c): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><?= e($c['titre']) ?></td>
                    <td><span class="tag"><?= e($c['categorie']) ?></span></td>
                    <td><?= e(date('d/m/Y', strtotime($c['date_creation']))) ?></td>
                    <td class="actions">
                        <a class="btn btn-outline btn-sm" href="<?= url('modifier_conseil', ['id' => $c['id']]) ?>">Modifier</a>
                        <form method="post" action="<?= url('supprimer_conseil') ?>" class="inline"
                              data-confirm="Voulez-vous vraiment supprimer ce conseil ?">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
