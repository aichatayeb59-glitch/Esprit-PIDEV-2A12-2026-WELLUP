<?php
/**
 * app/session_config.php  (NOUVEAU FICHIER - Partie 3 : sessions)
 *
 * Configuration sécurisée des sessions PHP.
 * À inclure AVANT session_start() (user_routes.php le fait déjà).
 *
 * - SESSION_LIFETIME : durée d'inactivité maximale en secondes.
 *   1800 = 30 minutes (valeur demandée). Pour tester l'expiration, mettre 10 temporairement.
 */

if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 1800);
}

/**
 * Applique les réglages de sécurité des sessions.
 * Sans effet si une session est déjà démarrée (ini_set serait refusé).
 */
function configureSecureSession(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }
    ini_set('session.use_strict_mode', '1');   // refuse un identifiant de session inconnu (anti fixation)
    ini_set('session.use_only_cookies', '1');  // jamais d'ID de session dans l'URL
    ini_set('session.cookie_httponly', '1');   // cookie inaccessible depuis JavaScript (anti vol XSS)
    ini_set('session.cookie_samesite', 'Lax'); // limite l'envoi du cookie par d'autres sites (anti CSRF)
    ini_set('session.gc_maxlifetime', (string) SESSION_LIFETIME);

    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    ini_set('session.cookie_secure', $https ? '1' : '0'); // cookie HTTPS uniquement si le site est en HTTPS
}
