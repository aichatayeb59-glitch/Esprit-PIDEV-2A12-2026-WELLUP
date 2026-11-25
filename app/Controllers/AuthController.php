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
    // ===== PARTIE 3 (Connexion / Déconnexion / Sessions) : DÉBUT =====
    /** Nombre d'échecs de connexion autorisés avant blocage temporaire. */
    private const MAX_LOGIN_ATTEMPTS = 5;
    /** Durée du blocage (secondes) après trop d'échecs. */
    private const LOCKOUT_SECONDS = 300;
    /**
     * Hash bcrypt factice : permet d'exécuter password_verify() même quand l'e-mail
     * n'existe pas, pour que le temps de réponse ne révèle pas si le compte existe.
     */
    private const DUMMY_HASH = '$2y$10$7wP.Y0FGuF88tKZBhCjLOuPtfHqK9lqYWSv0wfO.UHXB3hedq73Qa';

    /**
     * Expiration par inactivité : true si l'utilisateur connecté n'a rien fait
     * depuis plus de $lifetime secondes (30 min = 1800 s par défaut dans le module).
     */
    public static function isSessionExpired(int $lifetime): bool
    {
        if (!self::isAuthenticated()) {
            return false;
        }
        $last = (int) ($_SESSION['last_activity'] ?? 0);
        return $last > 0 && (time() - $last) > $lifetime;
    }

    /** Marque l'activité de l'utilisateur connecté (renouvelle le délai d'inactivité). */
    public static function touchSession(): void
    {
        if (self::isAuthenticated()) {
            $_SESSION['last_activity'] = time();
        }
    }

    /** Détruit complètement la session : données, cookie et fichier serveur. */
    public static function destroySession(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /** Requête préparée (anti injection SQL) : cherche un compte par e-mail. */
    private function findByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare(
            'SELECT id, username, email, password, role, created_at FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    // ==============================================================
    // LOGIN
    // ==============================================================
    /** Secondes restantes avant la fin du blocage (0 = pas bloqué). */
    private function lockoutRemaining(): int
    {
        return max(0, (int) ($_SESSION['login_locked_until'] ?? 0) - time());
    }

    /** Enregistre un échec ; bloque la connexion après MAX_LOGIN_ATTEMPTS échecs. */
    private function registerFailedLogin(): void
    {
        $_SESSION['login_attempts'] = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
        if ($_SESSION['login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION['login_locked_until'] = time() + self::LOCKOUT_SECONDS;
            $_SESSION['login_attempts'] = 0;
        }
    }

    /**
     * @return string[] erreurs (tableau vide = connexion réussie)
     */
    public function login(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        // Protection contre le brute-force (limitation par session)
        $wait = $this->lockoutRemaining();
        if ($wait > 0) {
            $errors[] = "Trop de tentatives. Réessayez dans " . (int) ceil($wait / 60) . " minute(s).";
            return $errors;
        }

        $email    = trim((string) ($post['email'] ?? ''));
        $password = (string) ($post['password'] ?? '');

        if ($email === '' || $password === '') {
            $errors[] = "Veuillez saisir votre e-mail et votre mot de passe.";
            return $errors;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $errors[] = "Adresse e-mail invalide.";
            return $errors;
        }

        $user = $this->findByEmail($email);

        // password_verify() est TOUJOURS exécuté (hash factice si e-mail inconnu) :
        // même message et même durée de réponse, on ne révèle pas si l'e-mail existe.
        $hash  = $user ? $user->getPassword() : self::DUMMY_HASH;
        $valid = password_verify($password, $hash);

        if (!$user || !$valid) {
            $this->registerFailedLogin();
            $errors[] = "E-mail ou mot de passe incorrect.";
            return $errors;
        }

        // Succès : nouvel identifiant de session (anti session fixation)
        session_regenerate_id(true);
        unset($_SESSION['login_attempts'], $_SESSION['login_locked_until'], $_SESSION['csrf_token']);
        $_SESSION['user_id']       = $user->getId();
        $_SESSION['username']      = $user->getUsername();
        $_SESSION['role']          = $user->getRole();
        $_SESSION['last_activity'] = time();

        return []; // success
    }

    // ==============================================================
    // LOGOUT
    // ==============================================================
    /** Déconnexion : vide la session, supprime le cookie, détruit la session serveur. */
    public function logout(): void
    {
        self::destroySession();
    }

    /**
     * Logout requested by a form: requires a valid CSRF token (POST only).
     * Returns true if the session was destroyed.
     */
    public function logoutIfValid(array $post): bool
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return false;
        }
        $this->logout();
        return true;
    }

    // ==============================================================
    // MEMBER 4 : profile and account (currentUserOrLogout, updateProfile, changePassword)
    // ==============================================================


    // ==============================================================
    // MEMBER 5 : roles and admin backoffice (requireRole, syncSession, CRUD admin)
    // ==============================================================

}
