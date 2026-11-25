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
    // SESSION STATE
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

    // ==============================================================
    // ROLE CONTROL
    // ==============================================================
    public static function hasRole(string $role): bool
    {
        return self::isAuthenticated() && ($_SESSION['role'] ?? null) === $role;
    }

    /**
     * Redirects and stops execution if the user doesn't have the required role
     */
    public static function requireRole(string $role): void
    {
        self::requireAuth();
        if (($_SESSION['role'] ?? null) !== $role) {
            http_response_code(403);
            die('Access denied: you do not have the required permissions.');
        }
    }

    // ==============================================================
    // CSRF
    // ==============================================================
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

    // ==============================================================
    // DATA ACCESS (prepared statements - SQL injection protection)
    // ==============================================================
    private function existsByEmailOrUsername(string $email, string $username): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1');
        $stmt->execute(['email' => $email, 'username' => $username]);
        return (bool) $stmt->fetch();
    }

    /** Same check, but ignoring the account being edited. */
    private function existsForAnotherUser(string $email, string $username, int $id): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM users WHERE (email = :email OR username = :username) AND id <> :id LIMIT 1'
        );
        $stmt->execute(['email' => $email, 'username' => $username, 'id' => $id]);
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

    private function updateUserProfile(int $id, string $username, string $email): bool
    {
        try {
            $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email WHERE id = :id');
            return $stmt->execute(['username' => $username, 'email' => $email, 'id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    private function findByEmail(string $email): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    public function findById(int $id): ?UserModel
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? UserModel::fromArray($row) : null;
    }

    /**
     * Requires a valid session and returns the logged-in user.
     * The id ALWAYS comes from the session, never from the URL:
     * a user can only see / edit his own account.
     */
    public function currentUserOrLogout(): UserModel
    {
        self::requireAuth();
        $user = $this->findById((int) $_SESSION['user_id']);
        if (!$user) { // account deleted while the session was still active
            $this->logout();
            header('Location: index.php?action=login');
            exit;
        }
        // The database is the source of truth: keep the session in sync
        $_SESSION['role']     = $user->getRole();
        $_SESSION['username'] = $user->getUsername();
        return $user;
    }

    // ==============================================================
    // REGISTER
    // ==============================================================

    /** Shared validation of username + email (register and profile edit). */
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
    // LOGIN
    // ==============================================================
    public function login(array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        $email    = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';

        if ($email === '' || $password === '') {
            $errors[] = "Veuillez saisir votre e-mail et votre mot de passe.";
            return $errors;
        }

        $user = $this->findByEmail($email);

        // Deliberately generic message (never reveal whether the email exists)
        if (!$user || !password_verify($password, $user->getPassword())) {
            $errors[] = "E-mail ou mot de passe incorrect.";
            return $errors;
        }

        // Regenerate the session ID: protects against session fixation
        session_regenerate_id(true);
        $_SESSION['user_id']       = $user->getId();
        $_SESSION['username']      = $user->getUsername();
        $_SESSION['role']          = $user->getRole();
        $_SESSION['last_activity'] = time();

        return []; // success
    }

    // ==============================================================
    // LOGOUT
    // ==============================================================
    public function logout(): void
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
        session_destroy();
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
    // PROFILE (WELLUP use case: "Modifier le profil")
    // ==============================================================
    public function updateProfile(int $userId, array $post): array
    {
        $errors = [];

        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            $errors[] = "Jeton de sécurité invalide. Veuillez réessayer.";
            return $errors;
        }

        $username = trim($post['username'] ?? '');
        $email    = trim($post['email'] ?? '');

        $errors = $this->validateIdentity($username, $email);
        $errors = array_values(array_unique($errors));
        if (!empty($errors)) {
            return $errors;
        }

        if ($this->existsForAnotherUser($email, $username, $userId)) {
            $errors[] = "Cet e-mail ou ce nom d'utilisateur est déjà utilisé.";
            return $errors;
        }

        if (!$this->updateUserProfile($userId, $username, $email)) {
            $errors[] = "Une erreur est survenue lors de la mise à jour du profil.";
            return $errors;
        }

        $_SESSION['username'] = $username; // keep the session in sync
        return []; // success
    }

    // ==============================================================
    // ADMIN BACKOFFICE DATA
    // ==============================================================
    /**
     * Retrieves all users (used by the admin backoffice)
     * @return UserModel[]
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY id ASC');
        $rows = $stmt->fetchAll();
        return array_map(fn(array $row) => UserModel::fromArray($row), $rows);
    }

    /**
     * Counts users by role (used by the admin dashboard stats)
     */
    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    public function countAllUsers(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    // ==============================================================
    // ADMIN BACKOFFICE : session sync, UPDATE and DELETE users
    // ==============================================================

    /**
     * Re-reads the role from the database on every sensitive page.
     * A demoted or deleted user loses access immediately, without waiting
     * for the session to expire.
     */
    public function syncSession(): void
    {
        if (!self::isAuthenticated()) {
            return;
        }
        $user = $this->findById((int) $_SESSION['user_id']);
        if (!$user) {
            $this->logout();
            header('Location: index.php?action=login');
            exit;
        }
        $_SESSION['role']     = $user->getRole();
        $_SESSION['username'] = $user->getUsername();
    }

    /**
     * Admin edits any account (username, email, role).
     * Business rule: there must always be at least one administrator.
     * @return string[] errors (empty = success)
     */
    public function adminUpdateUser(int $targetId, array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Jeton de sécurité invalide. Veuillez réessayer."];
        }

        $target = $this->findById($targetId);
        if (!$target) {
            return ["Utilisateur introuvable."];
        }

        $username = trim($post['username'] ?? '');
        $email    = trim($post['email'] ?? '');
        $role     = (string) ($post['role'] ?? '');

        $errors = $this->validateIdentity($username, $email);
        if (!in_array($role, ['user', 'admin'], true)) {
            $errors[] = "Rôle invalide.";
        }
        $errors = array_values(array_unique($errors));
        if (!empty($errors)) {
            return $errors;
        }

        if ($this->existsForAnotherUser($email, $username, $targetId)) {
            return ["Cet e-mail ou ce nom d'utilisateur est déjà utilisé."];
        }

        if ($target->getRole() === 'admin' && $role !== 'admin' && $this->countByRole('admin') <= 1) {
            return ["Impossible de retirer le rôle admin au dernier administrateur."];
        }

        try {
            $stmt = $this->db->prepare(
                'UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id'
            );
            $stmt->execute(['username' => $username, 'email' => $email, 'role' => $role, 'id' => $targetId]);
        } catch (PDOException $e) {
            return ["Une erreur est survenue lors de la mise à jour."];
        }

        // If the admin edited his own account, keep his session in sync
        if ($targetId === (int) ($_SESSION['user_id'] ?? 0)) {
            $_SESSION['username'] = $username;
            $_SESSION['role']     = $role;
        }
        return [];
    }

    /**
     * Admin deletes an account.
     * Rules: not his own account, never the last administrator.
     * @return string[] errors (empty = success)
     */
    public function adminDeleteUser(int $targetId, int $actingAdminId, array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Jeton de sécurité invalide. Veuillez réessayer."];
        }
        if ($targetId === $actingAdminId) {
            return ["Vous ne pouvez pas supprimer votre propre compte."];
        }

        $target = $this->findById($targetId);
        if (!$target) {
            return ["Utilisateur introuvable."];
        }
        if ($target->getRole() === 'admin' && $this->countByRole('admin') <= 1) {
            return ["Impossible de supprimer le dernier administrateur."];
        }

        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $targetId]);
        } catch (PDOException $e) {
            return ["Une erreur est survenue lors de la suppression."];
        }
        return [];
    }

    // ==============================================================
    // CHANGE PASSWORD (use case: "Gérer son compte")
    // ==============================================================
    /**
     * The logged-in user changes his own password.
     * The current password must be confirmed first.
     * @return string[] errors (empty = success)
     */
    public function changePassword(int $userId, array $post): array
    {
        if (!$this->verifyCsrfToken($post['csrf_token'] ?? null)) {
            return ["Jeton de sécurité invalide. Veuillez réessayer."];
        }

        $current = (string) ($post['current_password'] ?? '');
        $new     = (string) ($post['new_password'] ?? '');
        $confirm = (string) ($post['new_password_confirm'] ?? '');

        $user = $this->findById($userId);
        if (!$user) {
            return ["Utilisateur introuvable."];
        }

        $errors = [];
        if ($current === '' || $new === '' || $confirm === '') {
            $errors[] = "Tous les champs sont obligatoires.";
        } elseif (!password_verify($current, $user->getPassword())) {
            $errors[] = "Le mot de passe actuel est incorrect.";
        }
        if ($new !== '' && strlen($new) < 8) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        }
        if ($new !== $confirm) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
        if ($new !== '' && $new === $current) {
            $errors[] = "Le nouveau mot de passe doit être différent de l'ancien.";
        }
        if (!empty($errors)) {
            return array_values(array_unique($errors));
        }

        try {
            $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
            $stmt->execute(['password' => password_hash($new, PASSWORD_DEFAULT), 'id' => $userId]);
        } catch (PDOException $e) {
            return ["Une erreur est survenue lors du changement du mot de passe."];
        }

        // New credentials: issue a fresh session identifier
        session_regenerate_id(true);
        return [];
    }
}
