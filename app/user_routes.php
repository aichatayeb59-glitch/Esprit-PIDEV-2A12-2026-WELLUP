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
require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/Controllers/AuthController.php';
configureSecureSession();           // sans effet si index.php a déjà démarré la session

if (AuthController::isSessionExpired(SESSION_LIFETIME)) {
    AuthController::destroySession();
    header('Location: index.php?action=login&expired=1');
    exit;
}
AuthController::touchSession();     // renouvelle le délai d'inactivité

// Pas de cache : le bouton « Précédent » ne doit pas rouvrir une page protégée après déconnexion
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');


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
    case 'home':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard'));
        } else {
            header('Location: index.php?action=login');
        }
        exit;

    case 'login':
        if (AuthController::isAuthenticated()) {
            header('Location: index.php?action=' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard'));
            exit;
        }
        $errors = [];
        $old = ['email' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $authController->login($_POST);
            if (empty($errors)) {
                header('Location: index.php?action=' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin' : 'dashboard'));
                exit;
            }
            $old['email'] = trim((string) ($_POST['email'] ?? '')); // jamais le mot de passe
        }
        $csrfToken = $authController->getCsrfToken();
        require $views . 'login.php';
        break;

    case 'logout':
        // Déconnexion : POST + jeton CSRF uniquement
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authController->logoutIfValid($_POST)) {
            header('Location: index.php?action=login&logged_out=1');
        } else {
            header('Location: index.php?action=home');
        }
        exit;


    // === Membre 4 : case 'dashboard', 'profile', 'edit_profile', 'account', 'change_password' ===


    // === Membre 5 : case 'admin', 'admin_edit_user', 'admin_delete_user' ===


    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}
