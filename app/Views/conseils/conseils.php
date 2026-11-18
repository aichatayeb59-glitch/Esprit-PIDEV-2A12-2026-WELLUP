<section class="page-head">
    <h1>Conseils bien-être</h1>
    <p class="muted">Des idées simples pour prendre soin de vous chaque jour.</p>
</section>

<form method="get" action="index.php" class="filter">
    <input type="hidden" name="page" value="conseils">
    <label for="categorie">Catégorie</label>
    <select id="categorie" name="categorie" onchange="this.form.submit()">
        <option value="">Toutes</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= e($c) ?>" <?= $c === $categorie ? 'selected' : '' ?>><?= e($c) ?></option>
        <?php endforeach; ?>
    </select>
    <noscript><button class="btn btn-primary btn-sm" type="submit">Filtrer</button></noscript>
</form>

<?php if (empty($conseils)): ?>
    <div class="empty">
        <h3>Aucun conseil disponible pour le moment</h3>
        <p class="muted">Revenez bientôt, de nouveaux conseils seront ajoutés.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($conseils as $c): ?>
            <article class="card">
                <span class="tag"><?= e($c['categorie']) ?></span>
                <h3><?= e($c['titre']) ?></h3>
                <p><?= nl2br(e($c['contenu'])) ?></p>
                <?php if (!empty($c['date_creation'])): ?>
                    <p class="muted small">Publié le <?= e(date('d/m/Y', strtotime($c['date_creation']))) ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
