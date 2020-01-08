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

$num_etu = "SELECT id_etu FROM 2019_10_02_DIEBOLD_LOUX_TPtest_REZS1_Note_unique";
$list_num_etu = $bdd->query($num_etu);
while ($id_etu_exist = $list_num_etu->fetch()) {
    if ($id_etu_sent == $id_etu_exist[0]) {
        echo $id_etu_sent . " authorized";
    }
}
