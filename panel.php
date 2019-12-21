<?php
// require "vendor/autoload.php";
// // recupération des variables d'environnement
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
// $servername = getenv('SERVERNAME');
// $dbname = getenv('DBNAME');
// $username = getenv('USERNAME');
// $password = getenv('PASSWORD');
// //Connection bdd
//     try {
//         $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//         $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     } catch (PDOException $e) {
//         echo "Connection failed: " . $e->getMessage();
//     }
// //Récupération Numéro Etudiant du formulaire
// $id_etu = $_POST["numEtu"];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <?php

    // $sql_all_notes = "SELECT name_pdf FROM global";
    // $list_notes = $bdd->query($sql_all_notes);
    // $totalNote = []; // tableau de toutes les notes de l'élève
    // while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    //     $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    //     $my_note = $bdd->query($sql_note);
    //     $noteEtudiant = $my_note->fetch();

    //     echo $note[0] . " -> " . $noteEtudiant[0] . "<br>";
    //     array_push($totalNote, $noteEtudiant[0]); // push de ces notes dans le tableau pour moyenne
    // }
    // $moyenne = array_sum($totalNote) / count($totalNote); // on fait la moyenne : Ensemble des notes du tableau / nbr de note
    // echo "<br> <p> Votre Moyenne est de : <strong> " . $moyenne . "</strong>";
    ?>
</body>

</html>