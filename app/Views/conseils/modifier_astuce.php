<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head">
    <h1>Modifier une astuce</h1>
    <p>Mettez à jour l'astuce ou changez le conseil auquel elle est rattachée.</p>
</div>
<form method="post" action="index.php?action=modifier_astuce&amp;id=<?= (int)$id ?>" class="panel form" novalidate>
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <?php require __DIR__ . '/_form_astuce_fields.php'; ?>
    <div class="form__actions">
        <button type="submit" class="btn btn--primary">Mettre à jour</button>
        <a href="index.php?action=astuces" class="btn btn--ghost">Annuler</a>
    </div>
</form>
<?php require __DIR__ . '/_footer.php'; ?>
