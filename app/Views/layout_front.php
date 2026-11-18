<?php
/**
 * Layout Front Office : assemble les partials et injecte $content.
 * Variables : $title, $currentPage, $content, $extraScripts (optionnel)
 */
$bodyClass = 'front';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/navbar.php';
?>
<main class="container page">
    <?php require __DIR__ . '/partials/flash.php'; ?>
    <?= $content ?>
</main>
<?php
require_once __DIR__ . '/partials/footer.php';
require_once __DIR__ . '/partials/scripts.php';
