<?php $currentPage = $currentPage ?? 'admin_conseils'; ?>
<!-- Sidebar (espace administrateur) -->
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= url('admin_conseils') ?>">
        <span class="brand-dot"></span> WELL<span>UP</span>
    </a>
    <nav>
        <p class="sidebar-title">Gestion</p>
        <a href="<?= url('admin_conseils') ?>" class="<?= isActive('admin_conseils', $currentPage) ?>">Liste des conseils</a>
        <a href="<?= url('ajouter_conseil') ?>" class="<?= isActive('ajouter_conseil', $currentPage) ?>">Ajouter un conseil</a>
        <p class="sidebar-title">Espace utilisateur</p>
        <a href="<?= url('conseils') ?>">Voir les conseils</a>
        <a href="<?= url('conseil_ia') ?>">Conseils IA</a>
    </nav>
</aside>
<div class="overlay" data-overlay></div>
<!-- /Sidebar -->
