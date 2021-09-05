<?php
// Dépendances
include_once('../vendor/autoload.php');

// Récupération des variables d'environnement
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
    $bdd->exec('SET NAMES utf8');
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$action = $_POST['action'] ?? '';
$semestre = isset($_POST['semestre']) ? intval($_POST['semestre']) : 0;

if ($action === 'updateRanking' && ($semestre > 0 && $semestre < 5)) {
    include '../assets/include/moy.php';

    $sqlCreate = "DROP TABLE IF EXISTS ranking_$semestre; CREATE TABLE IF NOT EXISTS ranking_$semestre (id_etu serial, moy_etu float NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $bdd->query($sqlCreate);

    $sqlAllEtu = 'SELECT id_etu FROM data_etu WHERE promo="MMI' . ceil($semestre / 2) . '"';
    $sqlAllEtu = $bdd->query($sqlAllEtu);
    $dataEtu = $sqlAllEtu->fetchAll(PDO::FETCH_COLUMN);

    foreach ($dataEtu as $idEtu) {
        $moyEtu = calcAverage($idEtu);
        $sqlInsert = "INSERT INTO ranking_$semestre (id_etu, moy_etu) VALUES ($idEtu, $moyEtu)";
        $bdd->query($sqlInsert);
    }
}
