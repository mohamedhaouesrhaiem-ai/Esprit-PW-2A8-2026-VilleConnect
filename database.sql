DROP DATABASE IF EXISTS villeconnect;
CREATE DATABASE villeconnect;
USE villeconnect;

CREATE TABLE Lieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

CREATE TABLE Evenement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_evenement DATETIME NOT NULL,
    lieu_id INT,
    FOREIGN KEY (lieu_id) REFERENCES Lieu(id) ON DELETE CASCADE
);

CREATE TABLE Inscription (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evenement_id INT,
    nom_participant VARCHAR(255) NOT NULL,
    email_participant VARCHAR(255) NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evenement_id) REFERENCES Evenement(id) ON DELETE CASCADE
);

INSERT INTO Lieu (nom, adresse, ville) VALUES 
('Salle des Fêtes', 'Avenue Habib Bourguiba', 'Tunis'),
('Théâtre Municipal', 'Rue de la Liberté', 'Tunis'),
('Espace Culturel', 'Centre Ville', 'Ariana');
