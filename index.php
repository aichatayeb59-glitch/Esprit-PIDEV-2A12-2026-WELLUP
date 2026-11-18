<?php
// Point d'entrée unique : index.php?page=conseils
session_start();

require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/Models/Model.php';
require_once __DIR__ . '/app/Models/Conseil.php';
require_once __DIR__ . '/app/Controllers/Controller.php';
require_once __DIR__ . '/app/Controllers/ConseilController.php';

$page = $_GET['page'] ?? 'conseils';
require __DIR__ . '/app/routes.php';
