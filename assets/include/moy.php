<?php
$sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note FROM global";
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes de l'élève
$totalCoeff = [];
while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $sqlNote = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    $myNote = $bdd->query($sqlNote);
    $noteEtudiant = $myNote->fetch();
    if ($noteEtudiant[0] > $note[1] && $note['type_note'] == "Note unique") {
        $noteEtudiant = $noteEtudiant[0] * $note["note_coeff"];
        array_push($totalNote, $noteEtudiant); // push de ces notes dans le tableau pour moyenne
        array_push($totalCoeff, $note["note_coeff"]);
    }
}
$moyenne = array_sum($totalNote) / array_sum($totalCoeff); // on fait la moyenne : Ensemble des notes du tableau / tot de coeff
$moyenne = round($moyenne, 2);
