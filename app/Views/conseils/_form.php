<?php
// Formulaire partagé ajout / modification
// Variables : $action (URL), $errors, $old, $categories, $submitLabel
?>
<?php if (!empty($errors['global'])): ?>
    <div class="alert alert-error"><?= e($errors['global']) ?></div>
<?php endif; ?>

<form method="post" action="<?= e($action) ?>" class="form" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">

    <div class="field <?= isset($errors['titre']) ? 'has-error' : '' ?>">
        <label for="titre">Titre *</label>
        <input type="text" id="titre" name="titre" maxlength="150" value="<?= e($old['titre']) ?>" required>
        <?php if (isset($errors['titre'])): ?><small class="error"><?= e($errors['titre']) ?></small><?php endif; ?>
    </div>

    <div class="field <?= isset($errors['categorie']) ? 'has-error' : '' ?>">
        <label for="categorie">Catégorie *</label>
        <select id="categorie" name="categorie" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= e($c) ?>" <?= $c === $old['categorie'] ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['categorie'])): ?><small class="error"><?= e($errors['categorie']) ?></small><?php endif; ?>
    </div>

    <div class="field <?= isset($errors['contenu']) ? 'has-error' : '' ?>">
        <label for="contenu">Contenu *</label>
        <textarea id="contenu" name="contenu" rows="6" required><?= e($old['contenu']) ?></textarea>
        <?php if (isset($errors['contenu'])): ?><small class="error"><?= e($errors['contenu']) ?></small><?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
        <a class="btn btn-outline" href="<?= url('admin_conseils') ?>">Annuler</a>
    </div>
</form>
