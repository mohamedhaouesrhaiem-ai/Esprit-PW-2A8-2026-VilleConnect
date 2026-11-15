-- Module Commerces & offres locales (Eya Ben Said)
-- A importer APRES database.sql (qui cree la base villeconnect).
USE villeconnect;

DROP TABLE IF EXISTS Offre, Commerce, CategorieCommerce;

CREATE TABLE CategorieCommerce (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE Commerce (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    adresse VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    categorie_id INT NOT NULL,
    FOREIGN KEY (categorie_id) REFERENCES CategorieCommerce(id)
);

CREATE TABLE Offre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commerce_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    reduction TINYINT UNSIGNED NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    FOREIGN KEY (commerce_id) REFERENCES Commerce(id) ON DELETE CASCADE
);

INSERT INTO CategorieCommerce (nom) VALUES
('Alimentation'), ('Café & Restaurant'), ('Mode & Vêtements'), ('Santé & Beauté'), ('Services');

INSERT INTO Commerce (nom, description, adresse, telephone, categorie_id) VALUES
('Boulangerie El Baraka', 'Pain frais et viennoiseries faits maison chaque matin.', 'Avenue Habib Bourguiba, Tunis', '71123456', 1),
('Café Les Jasmins', 'Café traditionnel avec terrasse au coeur de la médina.', 'Rue de la Liberté, Tunis', '71234567', 2),
('Boutique Dar Mode', 'Prêt-à-porter et accessoires de créateurs tunisiens.', 'Centre Ville, Ariana', '70345678', 3),
('Pharmacie du Lac', 'Conseils santé, parapharmacie et produits de beauté.', 'Les Berges du Lac, Tunis', '71456789', 4);

INSERT INTO Offre (commerce_id, titre, description, reduction, date_debut, date_fin) VALUES
(1, 'Viennoiseries du matin', '-20% sur toutes les viennoiseries avant 10h.', 20, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
(2, 'Happy hour', '-15% sur les boissons chaudes de 15h à 17h.', 15, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY)),
(3, 'Soldes de saison', '-30% sur la nouvelle collection.', 30, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 45 DAY));
