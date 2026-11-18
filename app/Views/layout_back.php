<?php
/**
 * Layout Back Office : head + sidebar + header + contenu + footer + scripts.
 * Variables : $title, $currentPage, $content, $extraScripts (optionnel)
 */
$bodyClass = 'back';
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>
<main class="main-wrapper">
    <?php require_once __DIR__ . '/partials/header.php'; ?>
    <div class="admin-content">
        <?php require __DIR__ . '/partials/flash.php'; ?>
        <?= $content ?>
    </div>
    <?php require_once __DIR__ . '/partials/footer_admin.php'; ?>
</main>
<?php
require_once __DIR__ . '/partials/scripts.php';
