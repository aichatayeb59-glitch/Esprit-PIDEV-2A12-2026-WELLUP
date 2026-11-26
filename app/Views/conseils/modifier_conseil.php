<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head">
    <h1>Modifier un conseil</h1>
    <p>Mettez à jour le titre, la catégorie ou le contenu du conseil.</p>
</div>

<form method="post" action="index.php?action=modifier&amp;id=<?= (int)$id ?>" class="panel form" novalidate>
    <input type="hidden" name="id" value="<?= (int)$id ?>">
    <?php require __DIR__ . '/_form_fields.php'; ?>
    <div class="form__actions">
        <button type="submit" class="btn btn--primary">Mettre à jour</button>
        <a href="index.php?action=admin" class="btn btn--ghost">Annuler</a>
    </div>
</form>
<?php require __DIR__ . '/_footer.php'; ?>
