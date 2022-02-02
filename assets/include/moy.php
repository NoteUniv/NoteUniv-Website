<?php
$sqlCheck = "SELECT count(*) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = 'noteuniv_website') AND (TABLE_NAME = 'global_$semestre')";
$doesExists = $bdd->query($sqlCheck);
$notExists = $doesExists->fetch()[0] === "0";

// Store all subjects in existing UE
$UESubjects = [];

if (!$notExists) {
    $sqlAllNotes = "SELECT note_code, note_semester FROM global_$semestre WHERE type_note NOT LIKE '%intermédiaire%' ORDER BY note_date_c";

    $listNotes = $bdd->query($sqlAllNotes);

    while ($note = $listNotes->fetch(PDO::FETCH_ASSOC)) {
        // Create a multi-dimensional array with note_semester as keys and note_code as values, values must be unique
        $UESubjects[$note['note_semester']][] = $note['note_code'];
        $UESubjects[$note['note_semester']] = array_unique($UESubjects[$note['note_semester']]);
    }
}

// Sort UE
ksort($UESubjects);

function calcAverage($idEtu, $perUE = false) {
    global $bdd, $semestre, $UESubjects, $listNotes;

    if ($idEtu === "1") {
        return random_int(12, 20);
    } else if ($GLOBALS['notExists'] || $listNotes->rowCount() === 0) {
        if ($perUE) {
            return [];
        }
        return random_int(15, 20);
    }

    // Get grades of the student per UE
    $UEGrades = [];

    foreach ($UESubjects as $ue => $subjects) {
        $UEMoyenne = 0;
        $UECoeff = 0;
        foreach ($subjects as $subject) {
            $sqlSem = "SELECT name_pdf, note_coeff FROM global_$semestre WHERE type_note NOT LIKE '%intermédiaire%' AND note_code = '$subject' ORDER BY note_date_c, id DESC";
            $sqlPDF = $bdd->query($sqlSem);
            $avgSubject = []; // Average of each subject
            while ($infoNote = $sqlPDF->fetch()) {
                $sqlSubject = $bdd->query("SELECT note_etu FROM `$infoNote[name_pdf]` WHERE id_etu = $idEtu");
                $noteEtu = $sqlSubject->fetch();
                if ($noteEtu[0] < 21) { // Not ABS
                    array_push($avgSubject, $noteEtu[0]);
                    $coeffSubject = $infoNote['note_coeff'];
                }
            }
            if (count($avgSubject) == 0) {
                continue;
            } else {
                $moyenneMat = round(array_sum($avgSubject) / count($avgSubject), 3);
            }
            $UEMoyenne += $moyenneMat * $coeffSubject;
            $UECoeff += $coeffSubject;
        }
        if ($UECoeff == 0) {
            continue;
        }
        $UEGrades[$ue] = [$UEMoyenne / $UECoeff, $UECoeff];
    }

    $average = 0;
    $totalCoeff = 0;
    foreach ($UEGrades as $ue => $averageGrade) {
        $average += $averageGrade[0] * $averageGrade[1];
        $totalCoeff += $averageGrade[1];
    }

    if ($totalCoeff == 0) {
        return 0;
    }

    if ($perUE) {
        return $UEGrades;
    } else {
        return round($average / $totalCoeff, 3);
    }
}
