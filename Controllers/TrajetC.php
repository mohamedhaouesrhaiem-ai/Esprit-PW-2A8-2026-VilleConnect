<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Trajet.php';

class TrajetC {
    const LIEUX = ['Centre Ville', 'Lac 1', 'Lac 2', 'Les Berges du Lac', 'Gare de Tunis',
        'Aéroport Tunis-Carthage', 'Bab Saadoun', 'Le Bardo', 'Manouba', 'El Menzah',
        'El Manar', 'Ennasr', 'Ariana', 'Ghazela', 'Charguia', 'Montplaisir', 'Belvédère',
        'Mutuelleville', 'Carthage', 'Sidi Bou Saïd', 'La Marsa', 'La Goulette', 'Radès',
        'Ben Arous', 'Mégrine', 'Ezzahra',
    ];
    // Requête de base : trajet + nom du conducteur + places encore libres.
    const SELECT_BASE = "SELECT t.*, u.nom AS conducteur_nom,
        (t.places - COALESCE((SELECT SUM(r.nb_places) FROM Reservation r
                              WHERE r.trajet_id = t.id AND r.statut = 'confirmee'), 0)) AS places_restantes
        FROM Trajet t JOIN Utilisateur u ON t.conducteur_id = u.id";

    // Seul point de contact avec la table Utilisateur (module de Mohamed Jaafer Hannechi).
    function getUtilisateurs() {
        return config::getConnexion()->query("SELECT id, nom FROM Utilisateur ORDER BY nom")->fetchAll();
    }


    function rechercher($depart = '', $arrivee = '', $date = '') {
        $sql = self::SELECT_BASE . " WHERE t.statut = 'actif' AND t.date_depart >= NOW()";
        $params = [];
        if ($depart !== '') { $sql .= " AND t.depart = ?"; $params[] = $depart; }
        if ($arrivee !== '') { $sql .= " AND t.arrivee = ?"; $params[] = $arrivee; }
       
        if ($date !== '') { $sql .= " AND DATE(t.date_depart) = ?"; $params[] = $date; }
        $sql .= " ORDER BY t.date_depart ASC";
        $q = config::getConnexion()->prepare($sql);
        $q->execute($params);
        return $q->fetchAll();
    }

    function getTrajet($id) {
        $q = config::getConnexion()->prepare(self::SELECT_BASE . " WHERE t.id = ?");
        $q->execute([$id]);
        return $q->fetch();
    }

    function getByConducteur($conducteurId) {
        $q = config::getConnexion()->prepare(self::SELECT_BASE . " WHERE t.conducteur_id = ? ORDER BY t.date_depart DESC");
        $q->execute([$conducteurId]);
        return $q->fetchAll();
    }

    function getAll() {
        return config::getConnexion()->query(self::SELECT_BASE . " ORDER BY t.date_depart DESC")->fetchAll();
    }

    // Contrôle de saisie commun à "proposer" et "modifier". Retourne [erreurs, données nettoyées].
    function validate($in) {
        $errors = [];
        $conducteur = filter_var($in['conducteur_id'] ?? null, FILTER_VALIDATE_INT);
        $depart = trim($in['depart'] ?? '');
        $arrivee = trim($in['arrivee'] ?? '');
        $date = trim($in['date_depart'] ?? '');
        $places = filter_var($in['places'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 8]]);
        $prix = filter_var(str_replace(',', '.', trim($in['prix'] ?? '')), FILTER_VALIDATE_FLOAT);

        $ids = array_map('intval', array_column($this->getUtilisateurs(), 'id'));
        if (!in_array($conducteur, $ids, true)) { $errors[] = "Conducteur invalide."; }
                if (!in_array($depart, self::LIEUX, true)) { $errors[] = "Veuillez choisir un lieu de départ dans la liste."; }
        if (!in_array($arrivee, self::LIEUX, true)) { $errors[] = "Veuillez choisir un lieu d'arrivée dans la liste."; }
        if ($depart !== '' && $depart === $arrivee) { $errors[] = "Le départ et l'arrivée doivent être différents."; }
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $date);
        if (!$dt || $dt->format('Y-m-d\TH:i') !== $date) { $errors[] = "Date de départ invalide."; }
        elseif ($dt <= new DateTime()) { $errors[] = "La date de départ doit être dans le futur."; }
        if ($places === false) { $errors[] = "Le nombre de places doit être compris entre 1 et 8."; }
        if ($prix === false || $prix < 0 || $prix > 999.99) { $errors[] = "Le prix doit être un nombre entre 0 et 999,99."; }

        return [$errors, [
            'conducteur_id' => $conducteur, 'depart' => $depart, 'arrivee' => $arrivee,
            'date_depart' => $dt ? $dt->format('Y-m-d H:i:s') : '', 'places' => $places, 'prix' => $prix,
        ]];
    }

    function addTrajet($t) {
        try {
            $sql = "INSERT INTO Trajet (conducteur_id, depart, arrivee, date_depart, places, prix) VALUES (?, ?, ?, ?, ?, ?)";
            return config::getConnexion()->prepare($sql)->execute([
                $t->getConducteurId(), $t->getDepart(), $t->getArrivee(), $t->getDateDepart(), $t->getPlaces(), $t->getPrix()
            ]);
        } catch (Exception $e) { return false; }
    }

    // L'appelant vérifie avant que le trajet appartient bien au conducteur et qu'il est actif.
    function updateTrajet($id, $conducteurId, $t) {
        try {
            $sql = "UPDATE Trajet SET depart = ?, arrivee = ?, date_depart = ?, places = ?, prix = ?
                    WHERE id = ? AND conducteur_id = ? AND statut = 'actif'";
            return config::getConnexion()->prepare($sql)->execute([
                $t->getDepart(), $t->getArrivee(), $t->getDateDepart(), $t->getPlaces(), $t->getPrix(), $id, $conducteurId
            ]);
        } catch (Exception $e) { return false; }
    }

    // Annule le trajet ET ses réservations confirmées.
    function annulerTrajet($id, $conducteurId) {
        $db = config::getConnexion();
        try {
            $db->beginTransaction();
            $q = $db->prepare("UPDATE Trajet SET statut = 'annule' WHERE id = ? AND conducteur_id = ? AND statut = 'actif'");
            $q->execute([$id, $conducteurId]);
            if ($q->rowCount() === 0) { $db->rollBack(); return false; }
            $db->prepare("UPDATE Reservation SET statut = 'annulee' WHERE trajet_id = ? AND statut = 'confirmee'")->execute([$id]);
            $db->commit();
            return true;
        } catch (Exception $e) {
            if ($db->inTransaction()) { $db->rollBack(); }
            return false;
        }
    }

    function deleteTrajet($id) {
        config::getConnexion()->prepare("DELETE FROM Trajet WHERE id = ?")->execute([$id]);
    }
}
?>
