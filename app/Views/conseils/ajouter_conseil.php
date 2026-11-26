<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head">
    <h1>Ajouter un conseil</h1>
    <p>Les champs marqués d'un <span class="req">*</span> sont obligatoires.</p>
</div>

<form method="post" action="index.php?action=ajouter" class="panel form" novalidate>
    <?php require __DIR__ . '/_form_fields.php'; ?>
    <div class="form__actions">
        <button type="submit" class="btn btn--primary">Enregistrer</button>
        <a href="index.php?action=admin" class="btn btn--ghost">Annuler</a>
    </div>
</form>
<?php require __DIR__ . '/_footer.php'; ?>
