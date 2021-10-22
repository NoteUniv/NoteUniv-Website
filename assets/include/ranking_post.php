<?php
session_start();
// Dépendances
require_once "../../vendor/autoload.php";

// Récupération des variables d'environnement
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
    $bdd->exec('SET NAMES utf8');
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    header('Location: ../../ranking.php');
}

$id_etu = $_SESSION['id_etu'];

if (!empty($_POST['rank']) && $_POST['rank'] === 'hide') {
    $bdd->query('UPDATE data_etu SET ranking = 0, changed_by = ' . intval($_COOKIE['idEtuFirst']) . ' WHERE id_etu = ' . $id_etu);
} elseif (!empty($_POST['rank']) && $_POST['rank'] === 'show') {
    $bdd->query('UPDATE data_etu SET ranking = 1, changed_by = ' . intval($_COOKIE['idEtuFirst']) . ' WHERE id_etu = ' . $id_etu);
}

header('Location: ../../ranking.php');
