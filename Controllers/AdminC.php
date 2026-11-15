<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Models/JournalAction.php';
require_once __DIR__ . '/JournalActionC.php';

class AdminC {


    function getUtilisateurs() {
        $sql = "SELECT * FROM Utilisateur";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    function getUtilisateur($id) {
        $sql = "SELECT * FROM Utilisateur WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }


    function getAdmins() {
        $sql = "SELECT * FROM Admin";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $admins = $query->fetchAll();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
        // on ajoute le nom et l'email de l'utilisateur de chaque admin
        foreach ($admins as $key => $admin) {
            $user = $this->getUtilisateur($admin['user_id']);
            $admins[$key]['nom'] = $user['nom'];
            $admins[$key]['email'] = $user['email'];
        }
        return $admins;
    }

  
    function getUserIdsAdmins() {
        $ids = [];
        foreach ($this->getAdmins() as $admin) {
            $ids[] = $admin['user_id'];
        }
        return $ids;
    }

    function getNomAdmin($adminId) {
        foreach ($this->getAdmins() as $admin) {
            if ($admin['id'] == $adminId) {
                return $admin['nom'];
            }
        }
        return '';
    }

    function addAdmin($admin) {
        $sql = "INSERT INTO Admin (user_id, niveau) VALUES (:user_id, :niveau)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'user_id' => $admin->getUserId(),
                'niveau' => $admin->getNiveau()
            ]);
            $this->journaliser('ajout_admin', 'Utilisateur #' . $admin->getUserId() . ' nommé ' . $admin->getNiveau());
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function deleteAdmin($id) {
       
        if ($id == ADMIN_COURANT) {
            return;
        }
        $sql = "DELETE FROM Admin WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            $this->journaliser('retrait_admin', 'Admin #' . $id . ' retiré');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

  

    function changerStatutUtilisateur($id, $statut) {
        if ($statut != 'actif' && $statut != 'bloque') {
            return;
        }
        $sql = "UPDATE Utilisateur SET statut = :statut WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['statut' => $statut, 'id' => $id]);
            if ($statut == 'bloque') {
                $this->journaliser('blocage', 'Utilisateur #' . $id . ' bloqué');
            } else {
                $this->journaliser('deblocage', 'Utilisateur #' . $id . ' débloqué');
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function getSignalements() {
        $signalements = $this->lister('Signalement');
        $statuts = $this->lister('Statut');
        
        foreach ($signalements as $key => $s) {
            $signalements[$key]['statut_nom'] = '';
            foreach ($statuts as $st) {
                if ($st['id'] == $s['statut_id']) {
                    $signalements[$key]['statut_nom'] = $st['nom'];
                }
            }
        }
        return $signalements;
    }

    function supprimerSignalement($id) {
        $sql = "DELETE FROM Signalement WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            $this->journaliser('suppression_signalement', 'Signalement #' . $id . ' supprimé (contenu inapproprié)');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }


    function lister($table) {
        $sql = "SELECT * FROM " . $table;
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }


    function repartition($tableGroupe, $colonne) {
        $resultat = [];
        $signalements = $this->lister('Signalement');
        foreach ($this->lister($tableGroupe) as $groupe) {
            $total = 0;
            foreach ($signalements as $s) {
                if ($s[$colonne] == $groupe['id']) {
                    $total++;
                }
            }
            $resultat[] = ['nom' => $groupe['nom'], 'total' => $total];
        }

     
        $max = 0;
        foreach ($resultat as $ligne) {
            if ($ligne['total'] > $max) {
                $max = $ligne['total'];
            }
        }
        foreach ($resultat as $key => $ligne) {
            if ($max > 0) {
                $resultat[$key]['pourcentage'] = $ligne['total'] * 100 / $max;
            } else {
                $resultat[$key]['pourcentage'] = 0;
            }
        }
        return $resultat;
    }

    function getStatistiques() {
        $bloques = 0;
        foreach ($this->lister('Utilisateur') as $u) {
            if ($u['statut'] == 'bloque') {
                $bloques++;
            }
        }
        return [
            'utilisateurs' => count($this->lister('Utilisateur')),
            'bloques'      => $bloques,
            'evenements'   => count($this->lister('Evenement')),
            'inscriptions' => count($this->lister('Inscription')),
            'signalements' => count($this->lister('Signalement')),
            'actions'      => count($this->lister('JournalAction')),
            'parStatut'    => $this->repartition('Statut', 'statut_id'),
            'parCategorie' => $this->repartition('CategorieSignalement', 'categorie_id'),
        ];
    }



    function journaliser($type, $description) {
        $journalC = new JournalActionC();
        $journalC->addAction(new JournalAction(ADMIN_COURANT, $type, $description));
    }
}
?>

