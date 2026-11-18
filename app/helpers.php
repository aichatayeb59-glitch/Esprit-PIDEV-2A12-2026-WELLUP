<?php
// Petites fonctions utilitaires partagées (vues + contrôleurs)

/** Échappe une chaîne pour l'affichage HTML (protection XSS) */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Construit une URL du type index.php?page=xxx&id=1 */
function url(string $page, array $params = []): string
{
    return 'index.php?' . http_build_query(array_merge(['page' => $page], $params));
}

/** L'utilisateur connecté est-il administrateur ? */
function isAdmin(): bool
{
    return ($_SESSION['role'] ?? 'user') === 'admin';
}

/** Retourne 'active' si la page du lien = page courante (sidebar / navbar) */
function isActive($page, $currentPage): string
{
    $pages = (array)$page;
    return in_array($currentPage, $pages, true) ? 'active' : '';
}

/** Message flash : stocké en session, affiché une seule fois */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/** Jeton CSRF pour les formulaires POST */
function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function checkCsrf(): bool
{
    return isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
}
