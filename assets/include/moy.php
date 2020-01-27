<?php
$sql_all_notes = "SELECT name_pdf, note_coeff, type_note, note_semester FROM global_s$semestre WHERE note_semester = 'UE1' OR note_semester = 'UE2'";
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes de l'élève
$totalCoeff = [];

while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $table_name = $note[0];
    $sqlNote = "SELECT note_etu FROM $table_name WHERE id_etu = $id_etu";
    $myNote = $bdd->query($sqlNote);
    $noteEtudiant = $myNote->fetch();
    if ($noteEtudiant[0] < 21 && ($note['type_note'] == "Note unique" || $note['type_note'] == "Moyenne de notes (+M)")) {
        $noteEtudiant = $noteEtudiant[0] * $note["note_coeff"];
        array_push($totalNote, $noteEtudiant); // push de ces notes dans le tableau pour moyenne
        array_push($totalCoeff, $note["note_coeff"]);
    }
}

if (array_sum($totalCoeff) == 0) {
    array_push($totalCoeff, 1);
}

$moyenne_raw = array_sum($totalNote) / array_sum($totalCoeff); // on fait la moyenne : Ensemble des notes du tableau / tot de coeff
$moyenne = round($moyenne_raw, 2);
