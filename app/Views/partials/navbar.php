<?php $currentPage = $currentPage ?? ''; ?>
<!-- Navbar (espace utilisateur) -->
<header class="navbar">
    <div class="container navbar-inner">
        <a class="brand" href="<?= url('conseils') ?>">
            <span class="brand-dot"></span> WELL<span>UP</span>
        </a>
        <button class="nav-toggle" type="button" aria-label="Menu" data-nav-toggle>&#9776;</button>
        <nav class="nav-links" id="navLinks">
            <a href="<?= url('conseils') ?>" class="<?= isActive('conseils', $currentPage) ?>">Conseils</a>
            <a href="<?= url('conseil_ia') ?>" class="<?= isActive('conseil_ia', $currentPage) ?>">Conseils IA</a>
            <?php if (isAdmin()): ?>
                <a href="<?= url('admin_conseils') ?>" class="nav-admin">Espace admin</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<!-- /Navbar -->
