<?php
// Point d'entrée unique : index.php?page=conseils
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
if (isset($_GET['action'])) {
    require __DIR__ . '/app/user_routes.php';
    exit;
}

require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/Models/Model.php';
require_once __DIR__ . '/app/Models/Conseil.php';
require_once __DIR__ . '/app/Controllers/Controller.php';
require_once __DIR__ . '/app/Controllers/ConseilController.php';

$page = $_GET['page'] ?? 'conseils';
require __DIR__ . '/app/routes.php';
