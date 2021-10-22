<?php
session_start();
// Dépendances
require "vendor/autoload.php";

// Recupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
$hostname = $_ENV['BDD_HOST'];
$dbname = $_ENV['BDD_NAME'];
$username = $_ENV['BDD_LOGIN'];
$password = $_ENV['BDD_PASSWD'];

// Connexion bdd
try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

$id_etu_sent = $_GET["num_etu"] ?? 1;
$force_login = isset($_GET["login"]) ? $_GET["login"] : false;
// Force change semester
$semester = 1; // First half or second half (1 or 0)

$num_etu = "SELECT promo, enabled FROM data_etu where id_etu = $id_etu_sent";
$data_etu = $bdd->query($num_etu);
$data_etu = $data_etu->fetch();

if ($data_etu) {
    // If the student allowed NoteUniv to display his marks
    if ($data_etu['enabled']) {
        // Set cookie semester
        if (!isset($_COOKIE['semestre']) || $_COOKIE['semestre'][0] !== 's') {
            if ($data_etu['promo'] === 'MMI1') {
                setcookie('semestre', 's' . 1 * 2 - $semester, strtotime('+360 days'));
                setcookie('promo', 'MMI', strtotime('+360 days'));
            } else if ($data_etu['promo'] === 'MMI2') {
                setcookie('semestre', 's' . 2 * 2 - $semester, strtotime('+360 days'));
                setcookie('promo', 'MMI', strtotime('+360 days'));
            } else {
                setcookie('semestre', $data_etu['promo'], strtotime('+360 days'));
                setcookie('promo', $data_etu['promo'], strtotime('+360 days'));
            }
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
