<?php
require_once __DIR__ . '/../../controllers/account.php';
require_once __DIR__ . '/layout.php';

frontHeader('WELLUP - Espace Utilisateur', 'dashboard');
?>
<main class="container">
    <section class="welcome-card">
        <h1>Bienvenue, <?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?> (@<?= htmlspecialchars($currentUser['username']) ?>)</h1>
        <p>Gérez votre compte et suivez vos séances d'activité physique.</p>
    </section>

    <section class="grid-cards">
        <div class="card">
            <h3>🏃 Enregistrer une activité</h3>
            <p>Ajoutez une nouvelle activité physique à votre journal.</p>
            <a href="add_activity.php" class="btn">Enregistrer une activité</a>
        </div>

        <div class="card">
            <h3>⏱️ Enregistrer la durée</h3>
            <p>Renseignez la durée de votre séance et votre niveau d'effort.</p>
            <a href="log_duration.php" class="btn">Enregistrer la durée</a>
        </div>

        <div class="card">
            <h3>📋 Consulter les activités</h3>
            <p>Parcourez l'ensemble de vos activités enregistrées.</p>
            <a href="activities.php" class="btn">Consulter les activités</a>
        </div>

        <div class="card">
            <h3>📈 Suivre les progrès</h3>
            <p>Consultez l'évolution de vos performances dans le temps.</p>
            <a href="progress.php" class="btn">Suivre les progrès</a>
        </div>
    </section>
</main>
<?php frontFooter(); ?>
