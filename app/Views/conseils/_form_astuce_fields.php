<input type="hidden" name="csrf" value="<?= $e($csrf) ?>">

<div class="field <?= isset($errors['conseil_id']) ? 'has-error' : '' ?>">
    <label for="conseil_id">Conseil lié <span class="req">*</span></label>
    <select id="conseil_id" name="conseil_id" required>
        <option value="">— Choisir un conseil —</option>
        <?php foreach ($conseils as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= (int)$values['conseil_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= $e($c['titre']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($errors['conseil_id'])): ?><small class="error"><?= $e($errors['conseil_id']) ?></small><?php endif; ?>
</div>

<div class="field <?= isset($errors['titre']) ? 'has-error' : '' ?>">
    <label for="titre">Titre de l'astuce <span class="req">*</span></label>
    <input type="text" id="titre" name="titre" maxlength="150" value="<?= $e($values['titre']) ?>" required>
    <?php if (isset($errors['titre'])): ?><small class="error"><?= $e($errors['titre']) ?></small><?php endif; ?>
</div>

<div class="field <?= isset($errors['description']) ? 'has-error' : '' ?>">
    <label for="description">Description <span class="req">*</span></label>
    <textarea id="description" name="description" rows="5" required><?= $e($values['description']) ?></textarea>
    <?php if (isset($errors['description'])): ?><small class="error"><?= $e($errors['description']) ?></small><?php endif; ?>
</div>

<div class="field <?= isset($errors['duree_minutes']) ? 'has-error' : '' ?>">
    <label for="duree_minutes">Durée (minutes, facultatif)</label>
    <input type="number" id="duree_minutes" name="duree_minutes" min="1" max="240" value="<?= $e($values['duree_minutes']) ?>">
    <?php if (isset($errors['duree_minutes'])): ?><small class="error"><?= $e($errors['duree_minutes']) ?></small><?php endif; ?>
</div>
