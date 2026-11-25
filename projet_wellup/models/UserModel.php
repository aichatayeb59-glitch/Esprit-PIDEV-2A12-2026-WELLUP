<?php
class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom, username, email, role, password_hash, created_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getAllUsers(): array {
        $stmt = $this->pdo->query("SELECT id, nom, prenom, username, email, role, created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile(int $id, string $nom, string $prenom, string $username, string $email): bool {
        $check = $this->pdo->prepare("SELECT id FROM users WHERE (email = :email OR username = :username) AND id != :id");
        $check->execute([':email' => $email, ':username' => $username, ':id' => $id]);
        if ($check->fetch()) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE users SET nom = :nom, prenom = :prenom, username = :username, email = :email WHERE id = :id");
        return $stmt->execute([
            ':nom'      => trim($nom),
            ':prenom'   => trim($prenom),
            ':username' => trim($username),
            ':email'    => trim($email),
            ':id'       => $id
        ]);
    }

    public function changePassword(int $id, string $currentPassword, string $newPassword): bool {
        $user = $this->findById($id);
        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return false;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :password_hash WHERE id = :id");
        return $stmt->execute([
            ':password_hash' => $hashedPassword,
            ':id'            => $id
        ]);
    }
}
