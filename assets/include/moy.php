<?php
switch ($semestre) { // en fct du semestre on fait une requete
    case '1':
        $sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note, note_semester FROM global_s1 WHERE note_semester = 'UE1' OR note_semester = 'UE2'";
        break;
    case '2':
        $sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note, note_semester FROM global_s2 WHERE note_semester = 'UE1' OR note_semester = 'UE2'";
        break;
    case '3':
        $sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note, note_semester FROM global_s3  WHERE note_semester = 'UE1' OR note_semester = 'UE2'";
        break;
    case '4':
        $sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note, note_semester FROM global_s4 WHERE note_semester = 'UE1' OR note_semester = 'UE2'";
        break;
    default:
        $sql_all_notes = "SELECT name_pdf, mini, note_coeff, type_note, note_semester FROM global_s1 WHERE note_semester = 'UE1' OR    note_semester = 'UE2'";
        break;
}
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes de l'élève
$totalCoeff = [];
while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $sqlNote = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
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
$moyenne = array_sum($totalNote) / array_sum($totalCoeff); // on fait la moyenne : Ensemble des notes du tableau / tot de coeff
$moyenne = round($moyenne, 2);
