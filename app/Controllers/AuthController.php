<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/UserModel.php';

class AuthController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    // ==============================================================
    // SHARED FOUNDATIONS (used by every member's part)
    // ==============================================================

    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Redirects and stops execution if the user is not authenticated
     */
    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function verifyCsrfToken(?string $token): bool
    {
        if (!$token || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public function findById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    /** Shared validation of username + email (register, profile edit, admin edit). */
    private function validateIdentity(string $username, string $email): array
    {
        $errors = [];
        if ($username === '' || $email === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        }
        if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
            $errors[] = "Le nom d'utilisateur doit contenir 3 à 50 caractères (lettres, chiffres, _ ou -).";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $errors[] = "Adresse e-mail invalide.";
        }
        return $errors;
    }

    // ==============================================================
    // MEMBER 2 : registration (register, existsByEmailOrUsername, insertUser)
    // ==============================================================


    // ==============================================================
    // MEMBER 3 : login, logout, logoutIfValid, session handling
    // ==============================================================


    // ==============================================================
    // MEMBER 4 : profile and account (currentUserOrLogout, updateProfile, changePassword)
    // ==============================================================


    // ==============================================================
    // MEMBER 5 : roles and admin backoffice (requireRole, syncSession, CRUD admin)
    // ==============================================================

}
