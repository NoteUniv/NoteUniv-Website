<?php
include("bdd.php");

$id_etu = $_POST["numEtu"];

$sql_all_notes = "SELECT name_pdf FROM global";
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes
while ($note = $list_notes->fetch()) {
    $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    $my_note = $bdd->query($sql_note);
    $mynote = $my_note->fetch();
    echo $note[0] . " -> " . $mynote[0] . "<br>";
    array_push($totalNote, $mynote[0]);
}
$moyenne = array_sum($totalNote) / count($totalNote);
echo "<br>";
echo "<p> Votre Moyenne est de : <strong> " . $moyenne . "</strong></p>";
