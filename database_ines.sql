-- Module Mobilité douce & trajets (Ines Missaoui)
USE villeconnect;

CREATE TABLE IF NOT EXISTS Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);
INSERT IGNORE INTO Utilisateur (nom, email) VALUES
('Ines Missaoui', 'ines@example.com'),
('Mohamed Houes Rhaiem', 'mohamed@example.com'),
('Adem Touati', 'adem@example.com'),
('Eya Ben Said', 'eya@example.com');


DROP TABLE IF EXISTS Reservation;
DROP TABLE IF EXISTS Trajet;

CREATE TABLE Trajet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conducteur_id INT NOT NULL,
    depart VARCHAR(100) NOT NULL,
    arrivee VARCHAR(100) NOT NULL,
    date_depart DATETIME NOT NULL,
    places TINYINT UNSIGNED NOT NULL,
    prix DECIMAL(6,2) NOT NULL DEFAULT 0,
    statut ENUM('actif','annule') NOT NULL DEFAULT 'actif',
    FOREIGN KEY (conducteur_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE Reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trajet_id INT NOT NULL,
    passager_id INT NOT NULL,
    nb_places TINYINT UNSIGNED NOT NULL DEFAULT 1,
    statut ENUM('confirmee','annulee') NOT NULL DEFAULT 'confirmee',
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trajet_id) REFERENCES Trajet(id) ON DELETE CASCADE,
    FOREIGN KEY (passager_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

INSERT INTO Trajet (conducteur_id, depart, arrivee, date_depart, places, prix) VALUES
(1, 'Tunis', 'Ariana', '2027-01-10 08:00:00', 3, 2.50),
(2, 'Ariana', 'La Marsa', '2027-01-11 17:30:00', 2, 3.00),
(1, 'Tunis', 'Sousse', '2027-01-15 07:15:00', 4, 12.00);
