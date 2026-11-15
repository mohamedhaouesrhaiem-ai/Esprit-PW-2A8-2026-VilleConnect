USE villeconnect;
DROP TABLE IF EXISTS Signalement, Statut, CategorieSignalement;
CREATE TABLE CategorieSignalement (id INT AUTO_INCREMENT PRIMARY KEY, nom VARCHAR(100) NOT NULL);
CREATE TABLE Statut (id INT AUTO_INCREMENT PRIMARY KEY, nom VARCHAR(50) NOT NULL);
CREATE TABLE Signalement (id INT AUTO_INCREMENT PRIMARY KEY, titre VARCHAR(255) NOT NULL, description TEXT, categorie_id INT, statut_id INT, FOREIGN KEY (categorie_id) REFERENCES CategorieSignalement(id), FOREIGN KEY (statut_id) REFERENCES Statut(id));
INSERT INTO CategorieSignalement (nom) VALUES ('Voirie'), ('Éclairage'), ('Propreté');
INSERT INTO Statut (nom) VALUES ('En attente'), ('En cours'), ('Résolu');
