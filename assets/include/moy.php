<?php
$sql_all_notes = "SELECT note_date_c, note_code, note_semester FROM global_s$semestre WHERE type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c";

$list_notes = $bdd->query($sql_all_notes);
$ue1 = []; // liste des note UE1
$ue2 = []; // liste des note UE1

while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $matiere = $note['note_code'];
    if (preg_match("/UE1$/", $note['note_semester'])) {
        array_push($ue1, $matiere);
    } else {
        array_push($ue2, $matiere);
    }
}

$ue1 = array_unique($ue1, SORT_STRING);
$ue2 = array_unique($ue2, SORT_STRING);

// UE 1
$moyenneDesMatieres = [];
foreach ($ue1 as $key => $value) {
    $sqlSem = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, name_teacher, type_note, note_semester, note_total, median, variance, deviation, type_exam FROM global_s$semestre WHERE note_code = '$value' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
    $ue1Sql = $bdd->query($sqlSem);
    $moyMatiere = []; // Moyenne de chaque matière
    while ($infoNote = $ue1Sql->fetch()) {
        $coeff = $infoNote['note_coeff'];
        $type = $infoNote['type_note'];
        $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
        $noteEtu = $myNote->fetch();
        if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
            array_push($moyMatiere, $noteEtu[0]);
            $coeffMatiere = $coeff;
        }
    }
    if (count($moyMatiere) == 0) {
        $moyenneMat = 0;
        $coeffMatiere = 0;
    } else {
        $moyenneMat = round(array_sum($moyMatiere) / count($moyMatiere), 3);
    }
    array_push($moyenneDesMatieres, ['moyMat' => $moyenneMat, 'coeff' => $coeffMatiere]);
}

$moyUe1 = 0;
$coeffUe1 = 0;
if (count($moyenneDesMatieres)) {
    for ($i = 0; $i < count($moyenneDesMatieres); $i++) {
        $moyUe1 += $moyenneDesMatieres[$i]['moyMat'] * $moyenneDesMatieres[$i]['coeff'];
        $coeffUe1 += $moyenneDesMatieres[$i]['coeff'];
    }
    $moyUe1 /= $coeffUe1;
} else {
    $coeffUe1 = 0;
    $moyUe1 = 0;
}

// UE 2
$moyenneDesMatieres = [];
foreach ($ue2 as $key => $value) {
    $sqlSem = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, name_teacher, type_note, note_semester, note_total, median, variance, deviation, type_exam FROM global_s$semestre WHERE note_code = '$value' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
    $ue2Sql = $bdd->query($sqlSem);
    $moyMatiere = []; // Moyenne de chaque matière
    while ($infoNote = $ue2Sql->fetch()) {
        $coeff = $infoNote['note_coeff'];
        $type = $infoNote['type_note'];
        $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
        $noteEtu = $myNote->fetch();
        if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
            array_push($moyMatiere, $noteEtu[0]);
            $coeffMatiere = $coeff;
        }
    }
    if (count($moyMatiere) == 0) {
        $moyenneMat = 0;
        $coeffMatiere = 0;
    } else {
        $moyenneMat = round(array_sum($moyMatiere) / count($moyMatiere), 3);
    }
    array_push($moyenneDesMatieres, ['moyMat' => $moyenneMat, 'coeff' => $coeffMatiere]);
}

$moyUe2 = 0;
$coeffUe2 = 0;
if (count($moyenneDesMatieres)) {
    for ($i = 0; $i < count($moyenneDesMatieres); $i++) {
        $moyUe2 += $moyenneDesMatieres[$i]['moyMat'] * $moyenneDesMatieres[$i]['coeff'];
        $coeffUe2 += $moyenneDesMatieres[$i]['coeff'];
    }
    $moyUe2 /= $coeffUe2;
} else {
    $coeffUe2 = 0;
    $moyUe2 = 0;
}

// Moyenne finale
$totalCoeff = $coeffUe1 + $coeffUe2;
if ($totalCoeff == 0) {
    $totalCoeff = 1;
}

$moyenne = round((($moyUe1 * $coeffUe1) + ($moyUe2 * $coeffUe2)) / ($totalCoeff), 3);
