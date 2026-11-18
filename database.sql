-- Base de données du module Gestion des conseils (à harmoniser avec la base commune WELLUP)
CREATE DATABASE IF NOT EXISTS wellup CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wellup;

CREATE TABLE IF NOT EXISTS conseils (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titre         VARCHAR(150) NOT NULL,
    categorie     VARCHAR(50)  NOT NULL,
    contenu       TEXT         NOT NULL,
    date_creation DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO conseils (titre, categorie, contenu) VALUES
('Boire assez d''eau', 'Hydratation', 'Buvez environ 1,5 litre d''eau par jour pour garder votre énergie.'),
('Un verre d''eau au réveil', 'Hydratation', 'Buvez un grand verre d''eau dès le matin pour bien commencer la journée.'),
('Manger des fruits et légumes', 'Nutrition', 'Ajoutez des fruits et des légumes à chaque repas.'),
('Une heure de coucher régulière', 'Sommeil', 'Couchez-vous à la même heure chaque soir pour mieux dormir.'),
('Marcher 30 minutes', 'Sport', 'Une marche rapide par jour est bonne pour le coeur et le moral.'),
('Respiration profonde', 'Stress', 'Respirez lentement pendant 5 minutes pour calmer votre stress.'),
('Notez trois choses positives', 'Mental', 'Chaque soir, écrivez trois choses positives de votre journée.');
