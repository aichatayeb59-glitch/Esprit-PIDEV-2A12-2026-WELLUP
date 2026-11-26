<?php
/** Connexion PDO partagée par les modèles. */
class Database
{
    public static function pdo(): PDO
    {
        static $pdo = null;
        if ($pdo === null) {
            $cfg = require __DIR__ . '/../../config/database.php';
            $pdo = new PDO(
                "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4",
                $cfg['user'],
                $cfg['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return $pdo;
    }
}
