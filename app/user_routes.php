<?php
declare(strict_types=1);

/**
 * app/user_routes.php
 * Routage du module « Gestion des utilisateurs » (index.php?action=...).
 * Inclus par index.php quand le paramètre ?action= est présent.
 * Chaque membre ajoute ses blocs à l'emplacement qui porte son numéro.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// === Membre 3 : expiration de la session après 30 minutes d'inactivité ===


// === Membre 6 : en-têtes de sécurité HTTP ===


require_once __DIR__ . '/Controllers/AuthController.php';
$authController = new AuthController();

// Jeton CSRF disponible pour toutes les vues
$csrfToken = $authController->getCsrfToken();

$action = $_GET['action'] ?? 'home';
$views = __DIR__ . '/Views/utilisateurs/';

switch ($action) {

    // === Membre 2 : case 'register' ===


    // === Membre 3 : case 'home', 'login', 'logout' ===


    // === Membre 4 : case 'dashboard', 'profile', 'edit_profile', 'account', 'change_password' ===


    // === Membre 5 : case 'admin', 'admin_edit_user', 'admin_delete_user' ===


    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}
