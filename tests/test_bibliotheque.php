<?php
// Tests pour la classe Bibliotheque

$bib = new Bibliotheque();

// Test : bibliotheque vide
verifier($bib->compter() === 0, 'Bibliotheque vide : compter() retourne 0');
verifier($bib->tous() === [], 'Bibliotheque vide : tous() retourne un tableau vide');

// Test : ajouter un livre
$l1 = new Livre('9782100545261', 'Algorithmique', 'Cormen');
$bib->ajouter($l1);
verifier($bib->compter() === 1, 'Apres ajout : compter() retourne 1');

// Test : ajouter un deuxieme livre
$l2 = new Livre('9780132350884', 'Clean Code', 'Robert Martin');
$bib->ajouter($l2);
verifier($bib->compter() === 2, 'Apres 2 ajouts : compter() retourne 2');

// Test : ajouter un troisieme livre
$l3 = new Livre('9780201633610', 'Design Patterns', 'Gamma');
$bib->ajouter($l3);
verifier($bib->compter() === 3, 'Apres 3 ajouts : compter() retourne 3');

// Test : ajout avec ISBN duplique
$exceptionDup = false;
try {
    $bib->ajouter(new Livre('9782100545261', 'Duplicate', 'Test'));
} catch (Exception $e) {
    $exceptionDup = true;
}
verifier($exceptionDup, 'Ajouter un ISBN deja present leve une exception');

// Test : trouver par ISBN existant
$trouve = $bib->trouver('9782100545261');
verifier($trouve !== null && $trouve->getTitre() === 'Algorithmique', 'trouver() retourne le bon livre');

// Test : trouver par ISBN inexistant
verifier($bib->trouver('0000000000') === null, 'trouver() retourne null si ISBN absent');

// Test : tous() retourne tous les livres
verifier(count($bib->tous()) === 3, 'tous() retourne les 3 livres');

// Test : rechercher par titre (insensible a la casse)
$res = $bib->rechercher('algo');
verifier(count($res) === 1 && $res[0]->getTitre() === 'Algorithmique', 'rechercher("algo") trouve Algorithmique');

// Test : rechercher par auteur
$res2 = $bib->rechercher('martin');
verifier(count($res2) === 1 && $res2[0]->getTitre() === 'Clean Code', 'rechercher("martin") trouve Clean Code');

// Test : rechercher sans resultat
$res3 = $bib->rechercher('python');
verifier(count($res3) === 0, 'rechercher("python") ne trouve rien');

// Test : rechercher qui retourne plusieurs resultats
$l4 = new Livre('9780596007126', 'Algo Avance', 'Cormen');
$bib->ajouter($l4);
$res4 = $bib->rechercher('algo');
verifier(count($res4) === 2, 'rechercher("algo") trouve 2 livres');

// --- Tests : Statistiques ---

// Test : statistiques sur catalogue vide
$bibVide = new Bibliotheque();
$statsVide = $bibVide->statistiques();
verifier($statsVide['total'] === 0, 'Bibliotheque vide : statistiques total retourne 0');
verifier($statsVide['disponibles'] === 0, 'Bibliotheque vide : statistiques disponibles retourne 0');
verifier($statsVide['taux_disponibilite'] === 0.0, 'Bibliotheque vide : taux de disponibilite retourne 0.0');

// Test : statistiques sur catalogue avec 4 livres
$stats = $bib->statistiques();
verifier($stats['total'] === 4, 'statistiques() : total retourne 4');
verifier($stats['disponibles'] === 4, 'statistiques() : livres disponibles retourne 4');
verifier($stats['taux_disponibilite'] === 100.0, 'statistiques() : taux de disponibilite retourne 100.0%');

// Test : statistiques apres emprunt
if (method_exists($l1, 'emprunter')) {
    $l1->emprunter();
    $statsApresEmprunt = $bib->statistiques();
    verifier($statsApresEmprunt['disponibles'] === 3, 'statistiques() : disponibles passe a 3');
    verifier($statsApresEmprunt['taux_disponibilite'] === 75.0, 'statistiques() : taux de disponibilite passe a 75.0%');
}