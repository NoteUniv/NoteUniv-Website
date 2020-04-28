<?php
// Dépendances
require "vendor/autoload.php";

// Recupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
$hostname = getenv('BDD_HOST');
$dbname = getenv('BDD_NAME');
$username = getenv('BDD_LOGIN');
$password = getenv('BDD_PASSWD');

// Connexion bdd
try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$id_etu_sent = $_GET["num_etu"];

$num_etu = "SELECT id_etu FROM data_etu WHERE promo = 'mmi1'";
$list_num_etu = $bdd->query($num_etu);
while ($id_etu_exist = $list_num_etu->fetch()) {
    if ($id_etu_sent == $id_etu_exist[0]) {
        echo $id_etu_sent . " authorized";
        setcookie("semestre", "1", strtotime('+360 days'));
    }
}

$num_etu = "SELECT id_etu FROM data_etu WHERE promo = 'mmi2'";
$list_num_etu = $bdd->query($num_etu);
while ($id_etu_exist = $list_num_etu->fetch()) {
    if ($id_etu_sent == $id_etu_exist[0]) {
        echo $id_etu_sent . " authorized";
        setcookie("semestre", "3", strtotime('+360 days'));
    }
}
