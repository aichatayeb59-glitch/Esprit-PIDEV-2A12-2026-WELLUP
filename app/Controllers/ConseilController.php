<?php
/**
 * Contrôleur du module Gestion des conseils.
 * Reçoit les requêtes, valide les données, appelle le modèle, choisit la vue.
 */
require_once __DIR__ . '/../Models/Conseil.php';
require_once __DIR__ . '/../Models/Categorie.php';
require_once __DIR__ . '/../Models/Astuce.php';

class ConseilController
{
    private const VIEWS = __DIR__ . '/../Views/conseils/';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }
    }

    /* ---------- Utilitaires ---------- */

    private function isAdmin(): bool
    {
        return ($_SESSION['role'] ?? '') === 'admin';
    }

    private function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('acces_refuse', ['title' => 'Accès refusé']);
            exit;
        }
    }

    private function checkCsrf(): void
    {
        if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
            http_response_code(400);
            exit('Requête invalide (jeton CSRF).');
        }
    }

    private function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function redirect(string $action): void
    {
        header('Location: index.php?action=' . $action);
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        $data['isAdmin'] = $this->isAdmin();
        $data['csrf'] = $_SESSION['csrf'];
        $data['categories'] = Categorie::all();
        $data['flash'] = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        extract($data);
        require self::VIEWS . $view . '.php';
    }

    /** Valide les champs du formulaire ; retourne [valeurs, erreurs]. */
    private function validate(array $src): array
    {
        $v = [
            'titre'     => trim($src['titre'] ?? ''),
            'categorie_id' => (int)($src['categorie_id'] ?? 0),
            'contenu'   => trim($src['contenu'] ?? ''),
        ];
        $e = [];
        if ($v['titre'] === '') {
            $e['titre'] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($v['titre']) < 3 || mb_strlen($v['titre']) > 150) {
            $e['titre'] = 'Le titre doit contenir entre 3 et 150 caractères.';
        }
        if ($v['categorie_id'] <= 0) {
            $e['categorie'] = 'Veuillez choisir une catégorie.';
        } elseif (!Categorie::find($v['categorie_id'])) {
            $e['categorie'] = 'Catégorie invalide.';
        }
        if ($v['contenu'] === '') {
            $e['contenu'] = 'Le contenu du conseil est obligatoire.';
        } elseif (mb_strlen($v['contenu']) < 10) {
            $e['contenu'] = 'Le contenu doit contenir au moins 10 caractères.';
        }
        return [$v, $e];
    }

    /* ---------- Espace utilisateur ---------- */

    public function index(): void
    {
        $this->render('conseils', ['title' => 'Conseils de bien-être', 'conseils' => Conseil::all(), 'astuces' => Astuce::groupedByConseil()]);
    }

    public function ia(): void
    {
        $categorie = (int)($_POST['categorie_id'] ?? ($_GET['categorie_id'] ?? 0));
        $categorieNom = '';
        $recommandations = [];
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $cat = $categorie > 0 ? Categorie::find($categorie) : null;
            if (!$cat) {
                $erreur = 'Veuillez choisir un objectif bien-être dans la liste.';
            } else {
                $categorieNom = $cat['nom'];
                $recommandations = $this->genererRecommandationsSimulees($categorie);
            }
        }

        $this->render('conseil_ia', [
            'title' => 'Conseils IA',
            'categorie' => $categorie,
            'categorieNom' => $categorieNom,
            'recommandations' => $recommandations,
            'erreur' => $erreur,
            'submitted' => $_SERVER['REQUEST_METHOD'] === 'POST',
        ]);
    }

    /**
     * Génération SIMULÉE : aucune API IA n'est fournie (cf. cahier des charges §9).
     * Remplacer cette méthode par l'appel à l'API/au modèle choisi par l'équipe.
     */
    private function genererRecommandationsSimulees(int $categorie): array
    {
        $result = [];
        foreach (Conseil::byCategorie($categorie, 3) as $c) {
            $result[] = ['titre' => $c['titre'], 'texte' => $c['contenu']];
        }
        return $result;
    }

    /* ---------- Espace administrateur ---------- */

    public function admin(): void
    {
        $this->requireAdmin();
        $this->render('admin_conseils', ['title' => 'Gérer les conseils', 'conseils' => Conseil::all()]);
    }

    public function ajouter(): void
    {
        $this->requireAdmin();
        $values = ['titre' => '', 'categorie_id' => 0, 'contenu' => ''];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$values, $errors] = $this->validate($_POST);
            if (!$errors) {
                Conseil::create($values['titre'], $values['categorie_id'], $values['contenu']);
                $this->flash('success', 'Le conseil a été ajouté avec succès.');
                $this->redirect('admin');
            }
        }
        $this->render('ajouter_conseil', ['title' => 'Ajouter un conseil', 'values' => $values, 'errors' => $errors]);
    }

    public function modifier(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $conseil = $id > 0 ? Conseil::find($id) : null;
        if (!$conseil) {
            $this->flash('error', 'Conseil introuvable.');
            $this->redirect('admin');
        }

        $values = ['titre' => $conseil['titre'], 'categorie_id' => (int)$conseil['categorie_id'], 'contenu' => $conseil['contenu']];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$values, $errors] = $this->validate($_POST);
            if (!$errors) {
                Conseil::update($id, $values['titre'], $values['categorie_id'], $values['contenu']);
                $this->flash('success', 'Le conseil a été modifié avec succès.');
                $this->redirect('admin');
            }
        }
        $this->render('modifier_conseil', ['title' => 'Modifier un conseil', 'id' => $id, 'values' => $values, 'errors' => $errors]);
    }

    public function supprimer(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin');
        }
        $this->checkCsrf();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && Conseil::find($id)) {
            Conseil::delete($id);
            $this->flash('success', 'Le conseil a été supprimé.');
        } else {
            $this->flash('error', 'Conseil introuvable.');
        }
        $this->redirect('admin');
    }

    /* ---------- Astuces (2e table, liée à conseils) ---------- */

    private function validateAstuce(array $src): array
    {
        $duree = trim($src['duree_minutes'] ?? '');
        $v = [
            'conseil_id'    => (int)($src['conseil_id'] ?? 0),
            'titre'         => trim($src['titre'] ?? ''),
            'description'   => trim($src['description'] ?? ''),
            'duree_minutes' => $duree,
        ];
        $e = [];
        if ($v['conseil_id'] <= 0) {
            $e['conseil_id'] = 'Veuillez choisir un conseil.';
        } elseif (!Conseil::find($v['conseil_id'])) {
            $e['conseil_id'] = 'Conseil invalide.';
        }
        if ($v['titre'] === '') {
            $e['titre'] = "Le titre de l'astuce est obligatoire.";
        } elseif (mb_strlen($v['titre']) < 3 || mb_strlen($v['titre']) > 150) {
            $e['titre'] = 'Le titre doit contenir entre 3 et 150 caractères.';
        }
        if ($v['description'] === '') {
            $e['description'] = 'La description est obligatoire.';
        } elseif (mb_strlen($v['description']) < 5) {
            $e['description'] = 'La description doit contenir au moins 5 caractères.';
        }
        if ($duree !== '' && (!ctype_digit($duree) || (int)$duree < 1 || (int)$duree > 240)) {
            $e['duree_minutes'] = 'La durée doit être un nombre entre 1 et 240 minutes (ou vide).';
        }
        return [$v, $e];
    }

    private function dureeOuNull(array $v): ?int
    {
        return $v['duree_minutes'] === '' ? null : (int)$v['duree_minutes'];
    }

    public function astuces(): void
    {
        $this->requireAdmin();
        $this->render('admin_astuces', ['title' => 'Gérer les astuces', 'astuces' => Astuce::all()]);
    }

    public function ajouterAstuce(): void
    {
        $this->requireAdmin();
        $values = ['conseil_id' => (int)($_GET['conseil_id'] ?? 0), 'titre' => '', 'description' => '', 'duree_minutes' => ''];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$values, $errors] = $this->validateAstuce($_POST);
            if (!$errors) {
                Astuce::create($values['conseil_id'], $values['titre'], $values['description'], $this->dureeOuNull($values));
                $this->flash('success', "L'astuce a été ajoutée avec succès.");
                $this->redirect('astuces');
            }
        }
        $this->render('ajouter_astuce', ['title' => 'Ajouter une astuce', 'values' => $values, 'errors' => $errors, 'conseils' => Conseil::all()]);
    }

    public function modifierAstuce(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $astuce = $id > 0 ? Astuce::find($id) : null;
        if (!$astuce) {
            $this->flash('error', 'Astuce introuvable.');
            $this->redirect('astuces');
        }
        $values = [
            'conseil_id' => (int)$astuce['conseil_id'], 'titre' => $astuce['titre'],
            'description' => $astuce['description'], 'duree_minutes' => (string)($astuce['duree_minutes'] ?? ''),
        ];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            [$values, $errors] = $this->validateAstuce($_POST);
            if (!$errors) {
                Astuce::update($id, $values['conseil_id'], $values['titre'], $values['description'], $this->dureeOuNull($values));
                $this->flash('success', "L'astuce a été modifiée avec succès.");
                $this->redirect('astuces');
            }
        }
        $this->render('modifier_astuce', ['title' => 'Modifier une astuce', 'id' => $id, 'values' => $values, 'errors' => $errors, 'conseils' => Conseil::all()]);
    }

    public function supprimerAstuce(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('astuces');
        }
        $this->checkCsrf();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && Astuce::find($id)) {
            Astuce::delete($id);
            $this->flash('success', "L'astuce a été supprimée.");
        } else {
            $this->flash('error', 'Astuce introuvable.');
        }
        $this->redirect('astuces');
    }
}
