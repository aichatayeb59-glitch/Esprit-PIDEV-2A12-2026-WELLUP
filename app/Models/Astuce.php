<?php
require_once __DIR__ . '/Database.php';

/** Modèle Astuce : table `astuces` (liée à `conseils` par conseil_id). */
class Astuce
{
    private const SELECT = 'SELECT a.*, c.titre AS conseil_titre FROM astuces a JOIN conseils c ON c.id = a.conseil_id';

    public static function all(): array
    {
        return Database::pdo()->query(self::SELECT . ' ORDER BY a.conseil_id, a.id')->fetchAll();
    }

    /** Astuces regroupées par conseil : [conseil_id => [astuces...]] */
    public static function groupedByConseil(): array
    {
        $g = [];
        foreach (self::all() as $a) {
            $g[(int)$a['conseil_id']][] = $a;
        }
        return $g;
    }

    public static function find(int $id): ?array
    {
        $st = Database::pdo()->prepare(self::SELECT . ' WHERE a.id = :id');
        $st->execute([':id' => $id]);
        return $st->fetch() ?: null;
    }

    public static function create(int $conseilId, string $titre, string $description, ?int $duree): bool
    {
        $st = Database::pdo()->prepare(
            'INSERT INTO astuces (conseil_id, titre, description, duree_minutes, date_creation) VALUES (:cid, :t, :d, :du, NOW())'
        );
        return $st->execute([':cid' => $conseilId, ':t' => $titre, ':d' => $description, ':du' => $duree]);
    }

    public static function update(int $id, int $conseilId, string $titre, string $description, ?int $duree): bool
    {
        $st = Database::pdo()->prepare(
            'UPDATE astuces SET conseil_id = :cid, titre = :t, description = :d, duree_minutes = :du WHERE id = :id'
        );
        return $st->execute([':cid' => $conseilId, ':t' => $titre, ':d' => $description, ':du' => $duree, ':id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $st = Database::pdo()->prepare('DELETE FROM astuces WHERE id = :id');
        return $st->execute([':id' => $id]);
    }
}
