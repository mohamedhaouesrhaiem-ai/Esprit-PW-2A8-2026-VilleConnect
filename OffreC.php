<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Offre.php';

class OffreC {
    // Requête de base : offre + nom du commerce.
    const SELECT_BASE = "SELECT o.*, c.nom AS commerce_nom
        FROM Offre o JOIN Commerce c ON o.commerce_id = c.id";

    // $actives = true : uniquement les offres en cours (Front Office).
    function getOffres($commerce_id = '', $actives = false) {
        $db = config::getConnexion();
        try {
            $sql = self::SELECT_BASE . " WHERE 1 = 1";
            $params = [];
            if ($actives) { $sql .= " AND o.date_debut <= CURDATE() AND o.date_fin >= CURDATE()"; }
            if ($commerce_id !== '') { $sql .= " AND o.commerce_id = ?"; $params[] = $commerce_id; }
            $sql .= " ORDER BY o.date_fin ASC";
            $q = $db->prepare($sql);
            $q->execute($params);
            return $q->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function getOffre($id) {
        $q = config::getConnexion()->prepare("SELECT * FROM Offre WHERE id = ?");
        $q->execute([$id]);
        return $q->fetch();
    }

    // Seul point de contact avec la table Commerce pour les listes déroulantes.
    function getCommerces() {
        return config::getConnexion()->query("SELECT id, nom FROM Commerce ORDER BY nom")->fetchAll();
    }

    // Contrôle de saisie commun à "ajouter" et "modifier". Retourne [erreurs, données nettoyées].
    function validate($in) {
        $errors = [];
        $commerce_id = filter_var($in['commerce_id'] ?? null, FILTER_VALIDATE_INT);
        $titre = trim($in['titre'] ?? '');
        $description = trim($in['description'] ?? '');
        $reduction = filter_var($in['reduction'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 100]]);
        $date_debut = trim($in['date_debut'] ?? '');
        $date_fin = trim($in['date_fin'] ?? '');
        $commerceIds = array_map('intval', array_column($this->getCommerces(), 'id'));

        if (!in_array($commerce_id, $commerceIds, true)) { $errors[] = "Commerce invalide."; }
        if (mb_strlen($titre) < 3 || mb_strlen($titre) > 255) { $errors[] = "Le titre doit contenir entre 3 et 255 caractères."; }
        if ($description !== '' && mb_strlen($description) < 10) { $errors[] = "La description doit contenir au moins 10 caractères."; }
        if ($reduction === false) { $errors[] = "La réduction doit être un nombre entre 1 et 100."; }

        $dateOk = true;
        foreach (['date_debut' => $date_debut, 'date_fin' => $date_fin] as $champ => $valeur) {
            $d = DateTime::createFromFormat('Y-m-d', $valeur);
            if (!$d || $d->format('Y-m-d') !== $valeur) { $errors[] = "Date invalide (" . ($champ === 'date_debut' ? 'début' : 'fin') . ")."; $dateOk = false; }
        }
        if ($dateOk && $date_fin < $date_debut) { $errors[] = "La date de fin doit être après la date de début."; }

        return [$errors, [
            'commerce_id' => $commerce_id, 'titre' => $titre, 'description' => $description,
            'reduction' => $reduction, 'date_debut' => $date_debut, 'date_fin' => $date_fin,
        ]];
    }

    function addOffre($offre) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO Offre (commerce_id, titre, description, reduction, date_debut, date_fin) VALUES (?, ?, ?, ?, ?, ?)";
            $db->prepare($sql)->execute([
                $offre->getCommerceId(), $offre->getTitre(), $offre->getDescription(),
                $offre->getReduction(), $offre->getDateDebut(), $offre->getDateFin()
            ]);
        } catch (Exception $e) { die('Erreur ajout'); }
    }

    function updateOffre($id, $offre) {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE Offre SET commerce_id = ?, titre = ?, description = ?, reduction = ?, date_debut = ?, date_fin = ? WHERE id = ?";
            $db->prepare($sql)->execute([
                $offre->getCommerceId(), $offre->getTitre(), $offre->getDescription(),
                $offre->getReduction(), $offre->getDateDebut(), $offre->getDateFin(), $id
            ]);
        } catch (Exception $e) { die('Erreur modification'); }
    }

    function deleteOffre($id) {
        $db = config::getConnexion();
        $db->prepare("DELETE FROM Offre WHERE id = ?")->execute([$id]);
    }
}
?>
