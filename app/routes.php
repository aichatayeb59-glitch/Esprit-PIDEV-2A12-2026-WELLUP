<?php
// Table de routage : clé de page => [Controller, méthode]
$routes = [
    'conseils'         => ['ConseilController', 'index'],
    'conseil_ia'       => ['ConseilController', 'ia'],
    'admin_conseils'   => ['ConseilController', 'adminIndex'],
    'ajouter_conseil'  => ['ConseilController', 'ajouter'],
    'modifier_conseil' => ['ConseilController', 'modifier'],
    'supprimer_conseil'=> ['ConseilController', 'supprimer'],
    'dev_role'         => ['ConseilController', 'devRole'],
];

if (!isset($routes[$page])) {
    http_response_code(404);
    echo '404 - Page introuvable';
    exit;
}

[$class, $method] = $routes[$page];
(new $class())->$method();
