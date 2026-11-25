-- WELLUP : table des utilisateurs (Partie 2 : Inscription)
CREATE DATABASE IF NOT EXISTS wellup CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wellup;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(60)  NOT NULL,
    prenom        VARCHAR(60)  NOT NULL,
    username      VARCHAR(30)  NOT NULL UNIQUE,
    email         VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
