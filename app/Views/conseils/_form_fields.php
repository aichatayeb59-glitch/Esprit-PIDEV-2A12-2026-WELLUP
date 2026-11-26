<?php
/** Champs communs aux formulaires d'ajout et de modification. Variables : $values, $errors, $csrf */
?>
<input type="hidden" name="csrf" value="<?= $e($csrf) ?>">

<div class="field <?= isset($errors['titre']) ? 'has-error' : '' ?>">
    <label for="titre">Titre <span class="req">*</span></label>
    <input type="text" id="titre" name="titre" maxlength="150" value="<?= $e($values['titre']) ?>" required>
    <?php if (isset($errors['titre'])): ?><small class="error"><?= $e($errors['titre']) ?></small><?php endif; ?>
</div>

<div class="field <?= isset($errors['categorie']) ? 'has-error' : '' ?>">
    <label for="categorie">Catégorie <span class="req">*</span></label>
    <select id="categorie" name="categorie_id" required>
        <option value="">— Choisir une catégorie —</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>" <?= (int)$values['categorie_id'] === (int)$cat['id'] ? 'selected' : '' ?>><?= $e($cat['nom']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($errors['categorie'])): ?><small class="error"><?= $e($errors['categorie']) ?></small><?php endif; ?>
</div>

<div class="field <?= isset($errors['contenu']) ? 'has-error' : '' ?>">
    <label for="contenu">Contenu du conseil <span class="req">*</span></label>
    <textarea id="contenu" name="contenu" rows="7" required><?= $e($values['contenu']) ?></textarea>
    <?php if (isset($errors['contenu'])): ?><small class="error"><?= $e($errors['contenu']) ?></small><?php endif; ?>
</div>
