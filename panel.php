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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "note_univ";

    $id_etu = $_POST["numEtu"];

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $sql_all_notes = "SELECT name_pdf FROM global";
    $list_notes = $bdd->query($sql_all_notes);
    while ($note = $list_notes->fetch()) {
        $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
        $my_note = $bdd->query($sql_note);
        $mynote = $my_note->fetch();
        echo $note[0] . " -> " . $mynote[0] . "<br>";
    }
    ?>
</body>

</html>