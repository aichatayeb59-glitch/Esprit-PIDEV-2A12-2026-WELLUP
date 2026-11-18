<?php
// Modèle Conseil : toutes les opérations sur la table "conseils"

class Conseil extends Model
{
    /** Catégories disponibles (à harmoniser avec la base commune) */
    public const CATEGORIES = ['Nutrition', 'Hydratation', 'Sommeil', 'Sport', 'Stress', 'Mental'];

    public static function all(?string $categorie = null): array
    {
        if ($categorie) {
            $st = self::db()->prepare('SELECT * FROM conseils WHERE categorie = ? ORDER BY date_creation DESC');
            $st->execute([$categorie]);
        } else {
            $st = self::db()->query('SELECT * FROM conseils ORDER BY date_creation DESC');
        }
        return $st->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $st = self::db()->prepare('SELECT * FROM conseils WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public static function create(string $titre, string $categorie, string $contenu): bool
    {
        $st = self::db()->prepare(
            'INSERT INTO conseils (titre, categorie, contenu, date_creation) VALUES (?, ?, ?, NOW())'
        );
        return $st->execute([$titre, $categorie, $contenu]);
    }

    public static function update(int $id, string $titre, string $categorie, string $contenu): bool
    {
        $st = self::db()->prepare('UPDATE conseils SET titre = ?, categorie = ?, contenu = ? WHERE id = ?');
        return $st->execute([$titre, $categorie, $contenu, $id]);
    }

    public static function delete(int $id): bool
    {
        $st = self::db()->prepare('DELETE FROM conseils WHERE id = ?');
        return $st->execute([$id]);
    }

    /** Validation commune ajout / modification : retourne la liste des erreurs */
    public static function validate(array $data): array
    {
        $errors = [];
        $titre = trim($data['titre'] ?? '');
        $categorie = $data['categorie'] ?? '';
        $contenu = trim($data['contenu'] ?? '');

        if ($titre === '') {
            $errors['titre'] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($titre) > 150) {
            $errors['titre'] = 'Le titre ne doit pas dépasser 150 caractères.';
        }
        if (!in_array($categorie, self::CATEGORIES, true)) {
            $errors['categorie'] = 'Veuillez choisir une catégorie valide.';
        }
        if ($contenu === '') {
            $errors['contenu'] = 'Le contenu est obligatoire.';
        } elseif (mb_strlen($contenu) < 10) {
            $errors['contenu'] = 'Le contenu doit contenir au moins 10 caractères.';
        }
        return $errors;
    }
}
