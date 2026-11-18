<?php
// Contrôleur du module "Gestion des conseils"

class ConseilController extends Controller
{
    /* ---------- Espace UTILISATEUR ---------- */

    /** conseils.php : liste des conseils sous forme de cartes */
    public function index(): void
    {
        $this->page = 'conseils';
        $this->title = 'Conseils bien-être | WELLUP';

        $categorie = $_GET['categorie'] ?? '';
        if (!in_array($categorie, Conseil::CATEGORIES, true)) {
            $categorie = '';
        }
        $this->render('conseils', [
            'conseils'   => Conseil::all($categorie ?: null),
            'categories' => Conseil::CATEGORIES,
            'categorie'  => $categorie,
        ]);
    }

    /**
     * conseil_ia.php : recommandations personnalisées.
     * Contenu SIMULÉ (aucune API IA fournie) : on propose les conseils
     * de la catégorie liée à l'objectif choisi.
     */
    public function ia(): void
    {
        $this->page = 'conseil_ia';
        $this->title = 'Conseils IA | WELLUP';

        $objectifs = [
            'dormir_mieux'   => ['label' => 'Mieux dormir',      'categorie' => 'Sommeil'],
            'moins_stress'   => ['label' => 'Réduire le stress', 'categorie' => 'Stress'],
            'bouger_plus'    => ['label' => 'Bouger plus',       'categorie' => 'Sport'],
            'mieux_manger'   => ['label' => 'Mieux manger',      'categorie' => 'Nutrition'],
            'bien_hydrater'  => ['label' => 'Bien m\'hydrater',  'categorie' => 'Hydratation'],
            'moral'          => ['label' => 'Booster mon moral', 'categorie' => 'Mental'],
        ];

        $choix = $_GET['objectif'] ?? '';
        $resultats = null;
        if (isset($objectifs[$choix])) {
            $resultats = array_slice(Conseil::all($objectifs[$choix]['categorie']), 0, 3);
        }

        $this->render('conseil_ia', [
            'objectifs' => $objectifs,
            'choix'     => $choix,
            'resultats' => $resultats,
        ]);
    }

    /* ---------- Espace ADMINISTRATEUR ---------- */

    /** admin_conseils.php : tableau de gestion */
    public function adminIndex(): void
    {
        $this->requireAdmin();
        $this->layout = 'back';
        $this->page = 'admin_conseils';
        $this->title = 'Gestion des conseils | WELLUP Admin';

        $this->render('admin_conseils', ['conseils' => Conseil::all()]);
    }

    /** ajouter_conseil.php : formulaire + traitement */
    public function ajouter(): void
    {
        $this->requireAdmin();
        $this->layout = 'back';
        $this->page = 'ajouter_conseil';
        $this->title = 'Ajouter un conseil | WELLUP Admin';

        $errors = [];
        $old = ['titre' => '', 'categorie' => '', 'contenu' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = array_merge($old, array_map('trim', array_intersect_key($_POST, $old)));
            if (!checkCsrf()) {
                $errors['global'] = 'Session expirée, veuillez réessayer.';
            } else {
                $errors = Conseil::validate($old);
            }
            if (!$errors) {
                Conseil::create($old['titre'], $old['categorie'], $old['contenu']);
                setFlash('success', 'Le conseil a bien été ajouté.');
                $this->redirect('admin_conseils');
            }
        }

        $this->render('ajouter_conseil', [
            'errors' => $errors, 'old' => $old, 'categories' => Conseil::CATEGORIES,
        ]);
    }

    /** modifier_conseil.php : formulaire prérempli + traitement */
    public function modifier(): void
    {
        $this->requireAdmin();
        $this->layout = 'back';
        $this->page = 'admin_conseils';
        $this->title = 'Modifier un conseil | WELLUP Admin';

        $id = (int)($_GET['id'] ?? 0);
        $conseil = Conseil::find($id);
        if (!$conseil) {
            setFlash('error', 'Conseil introuvable.');
            $this->redirect('admin_conseils');
        }

        $errors = [];
        $old = [
            'titre' => $conseil['titre'],
            'categorie' => $conseil['categorie'],
            'contenu' => $conseil['contenu'],
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = array_merge($old, array_map('trim', array_intersect_key($_POST, $old)));
            if (!checkCsrf()) {
                $errors['global'] = 'Session expirée, veuillez réessayer.';
            } else {
                $errors = Conseil::validate($old);
            }
            if (!$errors) {
                Conseil::update($id, $old['titre'], $old['categorie'], $old['contenu']);
                setFlash('success', 'Le conseil a bien été modifié.');
                $this->redirect('admin_conseils');
            }
        }

        $this->render('modifier_conseil', [
            'id' => $id, 'errors' => $errors, 'old' => $old, 'categories' => Conseil::CATEGORIES,
        ]);
    }

    /** Suppression (POST uniquement, après confirmation JavaScript) */
    public function supprimer(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && checkCsrf()) {
            Conseil::delete((int)($_POST['id'] ?? 0));
            setFlash('success', 'Le conseil a été supprimé.');
        } else {
            setFlash('error', 'Action non autorisée.');
        }
        $this->redirect('admin_conseils');
    }

    /**
     * Outil de TEST uniquement : bascule le rôle utilisateur/admin
     * (à supprimer quand le module d'authentification de l'équipe sera branché).
     */
    public function devRole(): void
    {
        $_SESSION['role'] = ($_GET['role'] ?? 'user') === 'admin' ? 'admin' : 'user';
        $this->redirect($_SESSION['role'] === 'admin' ? 'admin_conseils' : 'conseils');
    }
}
