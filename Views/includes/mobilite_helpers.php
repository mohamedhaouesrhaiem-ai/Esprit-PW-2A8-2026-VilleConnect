<?php
// Aides communes aux pages du module Mobilité douce.

// Messages affichés après une action (le code vient de l'URL, le texte est fixe : pas de XSS).
function mobiliteFlash($code) {
    $map = [
        'cree'         => ['success', 'Trajet publié.'],
        'modifie'      => ['success', 'Trajet modifié.'],
        'annule'       => ['success', 'Trajet annulé (les réservations associées sont annulées).'],
        'reserve'      => ['success', 'Réservation confirmée.'],
        'resa_annulee' => ['success', 'Réservation annulée.'],
        'refuse'       => ['danger',  'Action non autorisée ou impossible.'],
    ];
    if (!isset($map[$code])) { return ''; }
    return "<div class='alert alert-{$map[$code][0]}'>" . htmlspecialchars($map[$code][1]) . "</div>";
}

// Liste déroulante des utilisateurs (remplacée plus tard par la session de connexion).
function mobiliteUserSelect($users, $selected, $name = 'uid', $autoSubmit = false) {
    $html = "<select name='" . htmlspecialchars($name) . "' class='form-select'" . ($autoSubmit ? " onchange='this.form.submit()'" : "") . " required>";
    $html .= "<option value=''>-- Choisir --</option>";
    foreach ($users as $u) {
        $sel = ((int)$u['id'] === (int)$selected) ? ' selected' : '';
        $html .= "<option value='" . (int)$u['id'] . "'$sel>" . htmlspecialchars($u['nom']) . "</option>";
    }
    return $html . "</select>";
}
function mobiliteLieuSelect($selected, $name, $tous = false) {
    $html = "<select name='" . htmlspecialchars($name) . "' class='form-select'" . ($tous ? "" : " required") . ">";
    $html .= "<option value=''>" . ($tous ? ($name === 'depart' ? 'Tous les départs' : 'Toutes les arrivées') : '-- Choisir --') . "</option>";
    foreach (TrajetC::LIEUX as $l) {
        $sel = ($l === $selected) ? ' selected' : '';
        $html .= "<option value='" . htmlspecialchars($l) . "'$sel>" . htmlspecialchars($l) . "</option>";
    }
    return $html . "</select>";
}

function mobiliteDate($d) { return date('d/m/Y H:i', strtotime($d)); }
?>
