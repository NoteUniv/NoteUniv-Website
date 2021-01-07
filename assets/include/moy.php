<?php
$sqlCheck = "SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = 'noteuniv_website') AND (TABLE_NAME = 'global_s$semestre')";
$doesExists = $bdd->query($sqlCheck);
$notExists = $doesExists->fetch()[0] === "0";

if ($notExists === true) {
    $moyenne = 20;
} else {
    $sqlAllNotes = "SELECT note_code, note_semester FROM global_s$semestre WHERE type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c";

    $listNotes = $bdd->query($sqlAllNotes);
    $ue1 = []; // Liste des notes UE1
    $ue2 = []; // Liste des notes UE2

    while ($note = $listNotes->fetch()) {
        $subject = $note['note_code'];
        if (preg_match("/UE1$/", $note['note_semester'])) {
            array_push($ue1, $subject);
        } else {
            array_push($ue2, $subject);
        }
    }

    $ue1Unique = array_unique($ue1, SORT_STRING);
    $ue2Unique = array_unique($ue2, SORT_STRING);
}

function calcAverage($idEtu)
{
    global $bdd, $semestre, $ue1Unique, $ue2Unique;

    // UE 1
    $averageSubjects = [];
    foreach ($ue1Unique as $noteType) {
        $sqlSem = "SELECT name_pdf, note_coeff FROM global_s$semestre WHERE note_code = '$noteType' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
        $ue1Sql = $bdd->query($sqlSem);
        $avgSubject = []; // Moyenne de chaque matière
        while ($infoNote = $ue1Sql->fetch()) {
            $coeff = $infoNote['note_coeff'];
            $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $idEtu");
            $noteEtu = $myNote->fetch();
            if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
                array_push($avgSubject, $noteEtu[0]);
                $coeffSubject = $coeff;
            }
        }
        if (count($avgSubject) == 0) {
            continue;
        } else {
            $moyenneMat = round(array_sum($avgSubject) / count($avgSubject), 3);
        }
        array_push($averageSubjects, ['moyMat' => $moyenneMat, 'coeff' => $coeffSubject]);
    }

    $moyUe1 = 0;
    $coeffUe1 = 0;
    if (count($averageSubjects)) {
        for ($i = 0; $i < count($averageSubjects); $i++) {
            $moyUe1 += $averageSubjects[$i]['moyMat'] * $averageSubjects[$i]['coeff'];
            $coeffUe1 += $averageSubjects[$i]['coeff'];
        }
        $moyUe1 /= $coeffUe1;
    }

    // UE 2
    $averageSubjects = [];
    foreach ($ue2Unique as $noteType) {
        $sqlSem = "SELECT name_pdf, note_coeff FROM global_s$semestre WHERE note_code = '$noteType' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
        $ue2Sql = $bdd->query($sqlSem);
        $avgSubject = []; // Moyenne de chaque matière
        while ($infoNote = $ue2Sql->fetch()) {
            $coeff = $infoNote['note_coeff'];
            $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $idEtu");
            $noteEtu = $myNote->fetch();
            if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
                array_push($avgSubject, $noteEtu[0]);
                $coeffSubject = $coeff;
            }
        }
        if (count($avgSubject) == 0) {
            continue;
        } else {
            $moyenneMat = round(array_sum($avgSubject) / count($avgSubject), 3);
        }
        array_push($averageSubjects, ['moyMat' => $moyenneMat, 'coeff' => $coeffSubject]);
    }

    $moyUe2 = 0;
    $coeffUe2 = 0;
    if (count($averageSubjects)) {
        for ($i = 0; $i < count($averageSubjects); $i++) {
            $moyUe2 += $averageSubjects[$i]['moyMat'] * $averageSubjects[$i]['coeff'];
            $coeffUe2 += $averageSubjects[$i]['coeff'];
        }
        $moyUe2 /= $coeffUe2;
    }

    // Moyenne finale
    $totalCoeff = $coeffUe1 + $coeffUe2;
    if ($totalCoeff == 0) {
        $totalCoeff = 1;
    }

    return round((($moyUe1 * $coeffUe1) + ($moyUe2 * $coeffUe2)) / ($totalCoeff), 3);
}
