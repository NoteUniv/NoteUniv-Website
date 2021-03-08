<?php
session_start();
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
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

$id_etu_sent = $_GET["num_etu"];
$force_login = $_GET["login"];
$semester = 0; // First half or second half (1 or 0)

$num_etu = "SELECT promo, enabled FROM data_etu where id_etu = $id_etu_sent";
$data_etu = $bdd->query($num_etu);
$data_etu = $data_etu->fetch();

if ($data_etu) {
    // If the student allowed NoteUniv to display his marks
    if ($data_etu['enabled']) {
        // Set cookie semester
        if ($data_etu['promo'] === 'MMI1') {
            setcookie('semestre', 1 * 2 - $semester, strtotime('+360 days'));
        } else {
            setcookie('semestre', 2 * 2 - $semester, strtotime('+360 days'));
        }
        echo $id_etu_sent . ' authorized';

        if ($force_login == true) {
            $_SESSION['id_etu'] = $id_etu_sent;
            header('Location: last.php');
        }
        // Else return an error
    } else {
        echo $id_etu_sent . ' disabled';
    }
}
