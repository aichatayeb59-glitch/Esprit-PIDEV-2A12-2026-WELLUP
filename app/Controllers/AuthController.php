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
    private function existsByEmailOrUsername(string $email, string $username): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $stmt->execute(['email' => $email, 'username' => $username]);
        return (bool) $stmt->fetch();
    }


    private function insertUser(UserModel $user): bool
    {
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)'
            );
            return $stmt->execute([
                'username' => $user->getUsername(),
                'email'    => $user->getEmail(),
                'password' => $user->getPassword(),
                'role'     => $user->getRole(),
            ]);
        } catch (PDOException $e) {
            return false; // e.g. duplicate key (race condition)
        }
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
    public function register(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        $username        = trim($post['username'] ?? '');
        $email           = trim($post['email'] ?? '');
        $password        = $post['password'] ?? '';
        $passwordConfirm = $post['password_confirm'] ?? '';

        $errors = $this->validateIdentity($username, $email);
        if ($password === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        }
        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($password !== $passwordConfirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
        $errors = array_values(array_unique($errors));
        if (!empty($errors)) {
            return $errors;
        }

        if ($this->existsByEmailOrUsername($email, $username)) {
            $errors[] = "Cet e-mail ou ce nom d'utilisateur est déjà utilisé.";
            return $errors;
        }

        // Secure password hashing (bcrypt via password_hash)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Build the entity (role forced to 'user' - no self-promotion to admin)
        $user = new UserModel();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setRole('user');

        if (!$this->insertUser($user)) {
            $errors[] = "Une erreur est survenue lors de la création du compte.";
        }

        return $errors; // empty array = success
    }

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
