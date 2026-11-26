<?php
require_once __DIR__ . '/Database.php';

/**
 * Modèle Conseil : table `conseils` (liée à `categories` par categorie_id).
 * Aucune logique d'affichage ici.
 */
class Conseil
{
    private const SELECT = 'SELECT c.*, cat.nom AS categorie FROM conseils c JOIN categories cat ON cat.id = c.categorie_id';

    public static function all(): array
    {
        return Database::pdo()->query(self::SELECT . ' ORDER BY c.date_creation DESC, c.id DESC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = Database::pdo()->prepare(self::SELECT . ' WHERE c.id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public static function byCategorie(int $categorieId, int $limit = 3): array
    {
        $st = Database::pdo()->prepare(self::SELECT . ' WHERE c.categorie_id = :c ORDER BY c.date_creation DESC LIMIT ' . (int)$limit);
        $st->execute([':c' => $categorieId]);
        return $st->fetchAll();
    }

    public static function create(string $titre, int $categorieId, string $contenu): bool
    {
        $st = Database::pdo()->prepare(
            'INSERT INTO conseils (titre, categorie_id, contenu, date_creation) VALUES (:t, :c, :ct, NOW())'
        );
        return $st->execute([':t' => $titre, ':c' => $categorieId, ':ct' => $contenu]);
    }

    public static function update(int $id, string $titre, int $categorieId, string $contenu): bool
    {
        $st = Database::pdo()->prepare(
            'UPDATE conseils SET titre = :t, categorie_id = :c, contenu = :ct WHERE id = :id'
        );
        return $st->execute([':t' => $titre, ':c' => $categorieId, ':ct' => $contenu, ':id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $st = Database::pdo()->prepare('DELETE FROM conseils WHERE id = :id');
        return $st->execute([':id' => $id]);
    }
}
