<?php
/**
 * Point d'entrée du module (routeur minimal).
 * Adapter au routeur/front controller du projet WELLUP.
 */
require_once __DIR__ . '/../app/Controllers/ConseilController.php';

$controller = new ConseilController();

// Aide au développement uniquement : simuler un rôle via ?as=admin|user.
// À SUPPRIMER et remplacer par le vrai système d'authentification du projet.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET['as']) && in_array($_GET['as'], ['admin', 'user'], true)) {
    $_SESSION['role'] = $_GET['as'];
}

$routes = [
    'index'     => 'index',
    'ia'        => 'ia',
    'admin'     => 'admin',
    'ajouter'   => 'ajouter',
    'modifier'  => 'modifier',
    'supprimer' => 'supprimer',
    'astuces'          => 'astuces',
    'ajouter_astuce'   => 'ajouterAstuce',
    'modifier_astuce'  => 'modifierAstuce',
    'supprimer_astuce' => 'supprimerAstuce',
];
$action = $_GET['action'] ?? 'index';

if (!isset($routes[$action])) {
    http_response_code(404);
    exit('Page introuvable.');
}
$controller->{$routes[$action]}();
