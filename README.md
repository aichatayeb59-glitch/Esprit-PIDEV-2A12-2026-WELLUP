# WELLUP — Module 06 : Gestion des conseils

## Lancer le projet
1. Importer `database.sql` dans MySQL (phpMyAdmin).
2. Adapter `app/config.php` (utilisateur / mot de passe).
3. Dans ce dossier : `php -S localhost:8000`
4. Ouvrir http://localhost:8000/index.php?page=conseils

## Pages
| Page (`?page=`) | Accès | Vue |
|---|---|---|
| conseils | Utilisateur | Views/conseils/conseils.php |
| conseil_ia | Utilisateur | Views/conseils/conseil_ia.php |
| admin_conseils | Admin | Views/conseils/admin_conseils.php |
| ajouter_conseil | Admin | Views/conseils/ajouter_conseil.php |
| modifier_conseil&id=1 | Admin | Views/conseils/modifier_conseil.php |

## Structure (MVC + atelier)
- `Controllers/` : Controller.php (render + layout), ConseilController.php
- `Models/` : Model.php (PDO), Conseil.php (CRUD + validation)
- `Views/partials/` : head, navbar, footer (front) — sidebar, header, footer_admin (back) — scripts, flash
- `Views/layout_front.php` et `layout_back.php` assemblent les partials ; les vues contiennent seulement le contenu.

## À savoir
- Le rôle admin est simulé : liens « Rôle de test » en bas de page (`?page=dev_role&role=admin`). À remplacer par le module d'authentification de l'équipe, puis supprimer `devRole`.
- Les conseils IA sont **simulés** (pas d'API fournie).
- Catégories : Nutrition, Hydratation, Sommeil, Sport, Stress, Mental (modifiables dans `Conseil::CATEGORIES`).
- Les templates réels (eLEARNING / TailAdmin) ne sont pas dans les fichiers reçus : le CSS `assets/css/wellup.css` (2 tons de vert) est un style provisoire. Pour utiliser le vrai template, copier ses CSS/JS dans `assets/` et les lier dans `partials/head.php` et `partials/scripts.php` ; les layouts ne changent pas.
