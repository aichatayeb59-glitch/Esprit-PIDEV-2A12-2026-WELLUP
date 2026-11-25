<?php require __DIR__ . '/_header.php'; ?>
<div class="page-head">
    <h1>Conseils de bien-être</h1>
    <p>Des conseils simples pour prendre soin de votre corps et de votre esprit au quotidien.</p>
</div>

<?php if (empty($conseils)): ?>
    <div class="empty">
        <h2>Aucun conseil disponible pour le moment</h2>
        <p>Revenez bientôt : de nouveaux conseils de bien-être seront publiés.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($conseils as $c): ?>
            <article class="card">
                <span class="badge"><?= $e($c['categorie']) ?></span>
                <h3><?= $e($c['titre']) ?></h3>
                <p><?= nl2br($e($c['contenu'])) ?></p>
                <?php if (!empty($astuces[(int)$c['id']])): ?>
                    <ul class="astuces">
                        <?php foreach ($astuces[(int)$c['id']] as $a): ?>
                            <li>
                                <strong><?= $e($a['titre']) ?></strong>
                                <?php if (!empty($a['duree_minutes'])): ?><span class="duree"><?= (int)$a['duree_minutes'] ?> min</span><?php endif; ?>
                                <br><span><?= $e($a['description']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (!empty($c['date_creation'])): ?>
                    <time class="card__date" datetime="<?= $e($c['date_creation']) ?>">
                        <?= $e(date('d/m/Y', strtotime($c['date_creation']))) ?>
                    </time>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/_footer.php'; ?>
