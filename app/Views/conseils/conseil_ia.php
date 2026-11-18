<section class="page-head">
    <h1>Conseils personnalisés par IA</h1>
    <p class="muted">Choisissez votre objectif et recevez des recommandations adaptées.</p>
</section>

<div class="notice">
    Version de démonstration : les recommandations sont simulées à partir des conseils existants
    (aucune API d'intelligence artificielle n'est connectée pour l'instant).
</div>

<form method="get" action="index.php" class="filter">
    <input type="hidden" name="page" value="conseil_ia">
    <label for="objectif">Mon objectif</label>
    <select id="objectif" name="objectif" required>
        <option value="">-- Choisir --</option>
        <?php foreach ($objectifs as $key => $o): ?>
            <option value="<?= e($key) ?>" <?= $key === $choix ? 'selected' : '' ?>><?= e($o['label']) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Obtenir mes conseils</button>
</form>

<section class="result-box">
    <h2>Mes recommandations</h2>
    <?php if ($resultats === null): ?>
        <p class="muted">Choisissez un objectif pour voir vos recommandations ici.</p>
    <?php elseif (empty($resultats)): ?>
        <p class="muted">Aucune recommandation disponible pour cet objectif pour le moment.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($resultats as $c): ?>
                <article class="card">
                    <span class="tag"><?= e($c['categorie']) ?></span>
                    <h3><?= e($c['titre']) ?></h3>
                    <p><?= nl2br(e($c['contenu'])) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
