
USE villeconnect;


CREATE TABLE IF NOT EXISTS Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    statut ENUM('actif', 'bloque') NOT NULL DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS JournalAction, Admin;

CREATE TABLE Admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    niveau ENUM('super_admin', 'moderateur') NOT NULL DEFAULT 'moderateur',
    date_nomination TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE JournalAction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    type_action VARCHAR(50) NOT NULL,
    description TEXT,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES Admin(id) ON DELETE CASCADE
);

INSERT IGNORE INTO Utilisateur (id, nom, email) VALUES
(1, 'Ela Beitah', 'admin@villeconnect.tn'),
(2, 'Sami Ben Ali', 'sami@example.com'),
(3, 'Ines Trabelsi', 'ines@example.com');

INSERT INTO Admin (user_id, niveau) VALUES (1, 'super_admin');

INSERT INTO JournalAction (admin_id, type_action, description) VALUES
(1, 'connexion', 'Première connexion au Back Office');
