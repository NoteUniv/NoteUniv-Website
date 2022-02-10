<?php
// Dépendances
include_once('../vendor/autoload.php');

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
    echo 'Connection failed: ' . $e->getMessage();
}

$action = $_POST['action'] ?? '';
$semestre = $_POST['semestre'] ?? 0;
$semestreMap = ['s1' => 'MMI1', 's2' => 'MMI1', 's3' => 'MMI2', 's4' => 'MMI2', 'lp_dweb' => 'LP_DWEB', 'lp_graph' => 'LP_GRAPH', 'lp_raj' => 'LP_RAJ'];

if ($action === 'updateRanking' && array_key_exists($semestre, $semestreMap)) {
    include '../assets/include/moy.php';

    $sqlCreate = "DROP TABLE IF EXISTS ranking_$semestre; CREATE TABLE IF NOT EXISTS ranking_$semestre (id_etu serial, moy_etu float NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $bdd->query($sqlCreate);

    $sqlAllEtu = "SELECT id_etu FROM data_etu WHERE promo='$semestreMap[$semestre]'";
    $sqlAllEtu = $bdd->query($sqlAllEtu);
    $dataEtu = $sqlAllEtu->fetchAll(PDO::FETCH_COLUMN);

    foreach ($dataEtu as $idEtu) {
        $moyEtu = calcAverage($idEtu, true);
        $sqlInsert = "INSERT INTO ranking_$semestre (id_etu, moy_etu) VALUES ($idEtu, $moyEtu)";
        $bdd->query($sqlInsert);
    }
}
