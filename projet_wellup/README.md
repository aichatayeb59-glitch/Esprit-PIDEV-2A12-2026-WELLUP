# WELLUP - Suivi de l'activité physique (Partie 4 : Profil et gestion du compte)

## Description du projet
Application web de suivi d'activité physique permettant aux utilisateurs de gérer leur profil, leur mot de passe, et d'accéder à leurs fonctionnalités principales (*Enregistrer une activité, Enregistrer la durée, Consulter les activités, Suivre les progrès*), organisée en **FrontOffice** et **BackOffice** (avec versions PHP et maquettes HTML).

## Structure des dossiers
```text
projet_wellup/
├── config/
│   └── database.php
├── controllers/
│   └── account.php
├── css/
│   └── wellup.css
├── js/
│   └── profile.js
├── models/
│   └── UserModel.php
├── sql/
│   └── users.sql
├── view/
│   ├── FrontOffice/
│   │   ├── index.php
│   │   ├── layout.php
│   │   ├── user_space.php
│   │   ├── profile.php
│   │   ├── edit_profile.php
│   │   ├── change_password.php
│   │   ├── index.html
│   │   ├── user_space.html
│   │   ├── profile.html
│   │   ├── edit_profile.html
│   │   └── change_password.html
│   └── BackOffice/
│       ├── user_accounts.php
│       └── user_accounts.html
└── README.md
```

## Installation
1. Importer `sql/users.sql` dans phpMyAdmin ou MySQL.
2. Configurer les accès BDD dans `config/database.php`.
3. Déployer `projet_wellup` sur votre serveur local (`htdocs` ou `www`).
