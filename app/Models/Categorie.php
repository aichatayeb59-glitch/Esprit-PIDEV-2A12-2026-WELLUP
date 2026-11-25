<?php
require_once __DIR__ . '/Database.php';

/** Modèle Categorie : table `categories`. */
class Categorie
{
    public static function all(): array
    {
        return Database::pdo()->query('SELECT * FROM categories ORDER BY nom')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = Database::pdo()->prepare('SELECT * FROM categories WHERE id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }
}
