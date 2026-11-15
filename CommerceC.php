<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Commerce.php';

class CommerceC {

    function getCommerces($categorie_id = '', $recherche = '') {
        $db = config::getConnexion();
        try {
            $sql = "SELECT c.*, cat.nom AS categorie_nom
                    FROM Commerce c
                    JOIN CategorieCommerce cat ON c.categorie_id = cat.id
                    WHERE 1 = 1";
            $params = [];
            if ($categorie_id !== '') { $sql .= " AND c.categorie_id = ?"; $params[] = $categorie_id; }
            if ($recherche !== '') { $sql .= " AND c.nom LIKE ?"; $params[] = '%' . $recherche . '%'; }
            $sql .= " ORDER BY c.nom";
            $q = $db->prepare($sql);
            $q->execute($params);
            return $q->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function getCommerce($id) {
        $q = config::getConnexion()->prepare("SELECT * FROM Commerce WHERE id = ?");
        $q->execute([$id]);
        return $q->fetch();
    }

    function getCategories() {
        return config::getConnexion()->query("SELECT * FROM CategorieCommerce ORDER BY nom")->fetchAll();
    }

    // Contrôle de saisie commun à "ajouter" et "modifier". Retourne [erreurs, données nettoyées].
    function validate($in) {
        $errors = [];
        $nom = trim($in['nom'] ?? '');
        $description = trim($in['description'] ?? '');
        $adresse = trim($in['adresse'] ?? '');
        $telephone = str_replace(' ', '', trim($in['telephone'] ?? ''));
        $categorie_id = filter_var($in['categorie_id'] ?? null, FILTER_VALIDATE_INT);
        $categorieIds = array_map('intval', array_column($this->getCategories(), 'id'));

        if (mb_strlen($nom) < 2 || mb_strlen($nom) > 255) { $errors[] = "Le nom doit contenir entre 2 et 255 caractères."; }
        if ($description !== '' && mb_strlen($description) < 10) { $errors[] = "La description doit contenir au moins 10 caractères."; }
        if (mb_strlen($adresse) < 3 || mb_strlen($adresse) > 255) { $errors[] = "L'adresse doit contenir entre 3 et 255 caractères."; }
        if (!preg_match('/^[0-9]{8}$/', $telephone)) { $errors[] = "Le téléphone doit contenir 8 chiffres."; }
        if (!in_array($categorie_id, $categorieIds, true)) { $errors[] = "Catégorie invalide."; }

        return [$errors, [
            'nom' => $nom, 'description' => $description, 'adresse' => $adresse,
            'telephone' => $telephone, 'categorie_id' => $categorie_id,
        ]];
    }

    function addCommerce($commerce) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO Commerce (nom, description, adresse, telephone, categorie_id) VALUES (?, ?, ?, ?, ?)";
            $db->prepare($sql)->execute([
                $commerce->getNom(), $commerce->getDescription(), $commerce->getAdresse(),
                $commerce->getTelephone(), $commerce->getCategorieId()
            ]);
        } catch (Exception $e) { die('Erreur ajout'); }
    }

    function updateCommerce($id, $commerce) {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE Commerce SET nom = ?, description = ?, adresse = ?, telephone = ?, categorie_id = ? WHERE id = ?";
            $db->prepare($sql)->execute([
                $commerce->getNom(), $commerce->getDescription(), $commerce->getAdresse(),
                $commerce->getTelephone(), $commerce->getCategorieId(), $id
            ]);
        } catch (Exception $e) { die('Erreur modification'); }
    }

    function deleteCommerce($id) {
        $db = config::getConnexion();
        $db->prepare("DELETE FROM Commerce WHERE id = ?")->execute([$id]);
    }
}
?>
