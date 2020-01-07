<?php
session_start();
require "vendor/autoload.php";
// recupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$servername = getenv('SERVERNAME');
$dbname = getenv('DBNAME');
$username = getenv('USER');
$password = getenv('PASSWORD');
// Connection bdd
    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
$bdd->query("TRUNCATE TABLE ranking");
//Récupération Numéro Etudiant du formulaire
$sql_all_num = "SELECT id_etu FROM 2019_10_02_DIEBOLD_LOUX_TPtest_REZS1_Note_unique";
$listNotes = $bdd->query($sql_all_num);
while ($id_etu = $listNotes->fetch()) {
    $sql_all_notes = "SELECT name_pdf, mini, note_coeff FROM global";
    $list_notes = $bdd->query($sql_all_notes);
    $totalNote = []; // tableau de toutes les notes de l'élève
    $totalCoeff = [];
    while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
        $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu[0]";
        $my_note = $bdd->query($sql_note);
        $noteEtudiant = $my_note->fetch();
        if ($noteEtudiant[0] > $note[1]) {
            $noteEtudiant = $noteEtudiant[0] * $note["note_coeff"];
            array_push($totalNote, $noteEtudiant); // push de ces notes dans le tableau pour moyenne
            array_push($totalCoeff, $note["note_coeff"]);
        }
    }
    $moyenne = array_sum($totalNote) / array_sum($totalCoeff); // on fait la moyenne : Ensemble des notes du tableau / tot de coeff
    $moyenne = round($moyenne, 2);
    $bdd->query("INSERT INTO ranking (id_etu, moy_etu) VALUES ($id_etu[0], $moyenne)");
}
