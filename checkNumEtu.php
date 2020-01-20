<?php
require "vendor/autoload.php";
// Recupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$servername = getenv('SERVERNAME');
$dbname = getenv('DBNAME');
$username = getenv('USER');
$password = getenv('PASSWORD');
// Connexion bdd
try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$id_etu_sent = $_GET["num_etu"];

$random_y1_sql = "SELECT `name_pdf` FROM `global_s1` ORDER BY RAND() LIMIT 1";
$random_y1 = $bdd->query($random_y1_sql)->fetch()[0];

$num_etu = "SELECT id_etu FROM " . $random_y1;
$list_num_etu = $bdd->query($num_etu);
while ($id_etu_exist = $list_num_etu->fetch()) {
    if ($id_etu_sent == $id_etu_exist[0]) {
        echo $id_etu_sent . " authorized";
        if (empty($_COOKIE['semestre'] || !is_numeric($_COOKIE['semestre']))) {
            setcookie("semestre", "1", strtotime('+360 days'));
        }
    }
}

$random_y3_sql = "SELECT `name_pdf` FROM `global_s3` ORDER BY RAND() LIMIT 1";
$random_y3 = $bdd->query($random_y3_sql)->fetch()[0];

$num_etu = "SELECT id_etu FROM " . $random_y3;
$list_num_etu = $bdd->query($num_etu);
while ($id_etu_exist = $list_num_etu->fetch()) {
    if ($id_etu_sent == $id_etu_exist[0]) {
        echo $id_etu_sent . " authorized";
        if (empty($_COOKIE['semestre'] || !is_numeric($_COOKIE['semestre']))) {
            setcookie("semestre", "3", strtotime('+360 days'));
        }
    }
}
