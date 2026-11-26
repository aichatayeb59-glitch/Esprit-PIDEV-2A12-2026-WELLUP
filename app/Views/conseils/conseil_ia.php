<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head">
    <h1>Conseils IA</h1>
    <p>Choisissez un objectif et recevez des recommandations personnalisées.</p>
</div>

<form method="post" action="index.php?action=ia" class="panel form form--inline">
    <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
    <div class="field">
        <label for="categorie">Mon objectif bien-être</label>
        <select id="categorie" name="categorie_id" required>
            <option value="">— Choisir —</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= (int)$categorie === (int)$cat['id'] ? 'selected' : '' ?>><?= $e($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn--primary">Obtenir mes recommandations</button>
</form>

<?php if (!empty($erreur)): ?>
    <div class="alert alert--error"><?= $e($erreur) ?></div>
<?php endif; ?>

<section class="result" aria-live="polite">
    <h2>Mes recommandations</h2>
    <?php if (!empty($recommandations)): ?>
        <div class="grid">
            <?php foreach ($recommandations as $r): ?>
                <article class="card card--ia">
                    <span class="badge badge--dark"><?= $e($categorieNom) ?></span>
                    <h3><?= $e($r['titre']) ?></h3>
                    <p><?= nl2br($e($r['texte'])) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php elseif (!empty($submitted) && empty($erreur)): ?>
        <div class="empty">
            <p>Aucune recommandation n'est disponible pour cet objectif pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="empty">
            <p>Vos recommandations apparaîtront ici après avoir choisi un objectif.</p>
        </div>
    <?php endif; ?>
    <p class="note">Recommandations générées en mode simulé (aucune API IA connectée pour l'instant).</p>
</section>
<?php require __DIR__ . '/_footer.php'; ?>
