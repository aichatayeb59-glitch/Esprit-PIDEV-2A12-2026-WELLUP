<section class="page-head">
    <h1>Modifier le conseil</h1>
    <p class="muted">Changez les informations puis enregistrez.</p>
</section>
<div class="panel">
<?php
$action = url('modifier_conseil', ['id' => $id]);
$submitLabel = 'Mettre à jour';
require __DIR__ . '/_form.php';
?>
</div>
