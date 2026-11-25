-- Module Gestion des conseils — WELLUP (3 tables : categories, conseils, astuces)
CREATE TABLE IF NOT EXISTS categories (
    id  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(60)  NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS conseils (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titre         VARCHAR(150) NOT NULL,
    categorie_id  INT UNSIGNED NOT NULL,
    contenu       TEXT         NOT NULL,
    date_creation DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_conseils_categorie FOREIGN KEY (categorie_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS astuces (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    conseil_id    INT UNSIGNED NOT NULL,
    titre         VARCHAR(150) NOT NULL,
    description   TEXT         NOT NULL,
    duree_minutes SMALLINT UNSIGNED NULL,
    date_creation DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_astuces_conseil FOREIGN KEY (conseil_id) REFERENCES conseils(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (nom) VALUES
('Sommeil'), ('Alimentation'), ('Activité physique'), ('Stress & mental'), ('Hydratation'), ('Général');

INSERT INTO conseils (titre, categorie_id, contenu) VALUES
('Dormir 7 à 8 heures par nuit', 1, 'Couchez-vous à heure régulière et évitez les écrans 30 minutes avant de dormir pour un sommeil plus réparateur.'),
('Manger plus de fruits et légumes', 2, 'Visez cinq portions de fruits et légumes de saison par jour pour faire le plein de vitamines et de fibres.'),
('Marcher 30 minutes par jour', 3, 'Une marche rapide quotidienne améliore la forme, l''humeur et la qualité du sommeil.'),
('Respirer pour se calmer', 4, 'Inspirez 4 secondes, retenez 4 secondes, expirez 6 secondes. Répétez pendant 2 minutes pour apaiser le stress.'),
('Boire de l''eau régulièrement', 5, 'Gardez une bouteille d''eau à portée de main et buvez environ 1,5 litre par jour, davantage en cas de chaleur ou d''effort.');

INSERT INTO astuces (conseil_id, titre, description, duree_minutes) VALUES
(1, 'Routine du soir', 'Baissez les lumières et lisez quelques pages avant de dormir.', 20),
(1, 'Chambre fraîche', 'Aérez la chambre et gardez-la autour de 18 °C.', 5),
(3, 'Pause active', 'Levez-vous toutes les heures pour marcher quelques minutes.', 5),
(4, 'Respiration 4-4-6', 'Asseyez-vous confortablement et suivez le rythme de respiration.', 2);
