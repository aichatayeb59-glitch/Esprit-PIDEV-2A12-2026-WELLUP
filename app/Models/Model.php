<?php
// Classe de base des modèles : fournit la connexion PDO

abstract class Model
{
    protected static ?PDO $pdo = null;

    protected static function db(): PDO
    {
        if (self::$pdo === null) {
            $c = require __DIR__ . '/../config.php';
            $dsn = "mysql:host={$c['host']};dbname={$c['dbname']};charset={$c['charset']}";
            self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
