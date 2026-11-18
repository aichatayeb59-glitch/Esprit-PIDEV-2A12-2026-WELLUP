<<<<<<< HEAD
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

WELLUP

Beyond Words — Small habits, better life.

1. Project Title

WELLUP – Health and Wellness Web Application

2. Overview

WELLUP is a web application designed to help users improve their daily lifestyle through simple and healthy habits.

It focuses on health, wellness, hydration, physical activity, and mental well-being.

Developed at Esprit School of Engineering – Tunisia.

3. Features

* Water consumption tracking.
* Physical activity tracking.
* Daily mood journal.
* Personal goals management.
* Health and wellness advice.
* Advice and categories management (CRUD).
* AI-powered daily recommendations.
* User accounts and role management.
* Administration and general statistics.

4. Tech Stack

Frontend:

* HTML
* CSS
* JavaScript

Backend:

* PHP
* Symfony
* MySQL

5. Architecture

The application follows the Model-View-Controller (MVC) architecture.

It separates application logic, user interface, and data management.

6. Contributors

Team: Beyond Words

1. Youssef – User Management (Registration, Login, Profile and Account Management).
2. Salsabil – Hydration Tracking (Water Consumption, Goals and History).
3. Mouheb – Physical Activity Tracking (Activities, Duration and Progress).
4. Anes – Mental Well-being (Mood Tracking and Emotional History).
5. Chahed – Goals Management (Creating, Editing and Tracking Personal Goals).
6. Aicha Tayeb – Advice Management (Wellness Advice, AI Advice and Consultation).
7. All Team Members – Administration and Statistics (Roles Management, Supervision and General Statistics).

7. Academic Context

This project is developed as part of an academic project at Esprit School of Engineering – Tunisia.

* Academic Year: 2026–2027
* Class: 2A12
* Project: PIDEV

8. Getting Started

Prerequisites

* PHP
* Composer
* Symfony
* MySQL
* A web browser

Installation

1. Clone the repository.
2. Install dependencies using Composer.
3. Configure the database connection.
4. Run the application.

9. Acknowledgments

We would like to thank our teachers and Esprit School of Engineering for their guidance and support throughout this project.

Beyond Words Team ❤️
>>>>>>> 7b0563c7d4307a95aed6cc4bc7178047cac8ea7e
