<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// WELLUP - Page d'accueil FrontOffice
require_once __DIR__ . '/layout.php';

frontHeader('WELLUP - Accueil', 'home');
?>
<main class="hero">
    <h1>Prenez soin de vous avec <span class="brand">WELLUP</span></h1>
    <p>Enregistrez vos activités physiques, suivez votre durée et visualisez vos progrès.</p>
    <a class="btn btn-inline" href="register.php">Créer un compte</a>
</main>
<?php frontFooter(); ?>
