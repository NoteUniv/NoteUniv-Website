<?php
session_start();
require "vendor/autoload.php";
// recupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$servername = getenv('SERVERNAME');
$dbname = getenv('DBNAME');
$username = getenv('USER');
$password = getenv('PASSWORD');
//Récupération Numéro Etudiant du formulaire
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
} else {
    header('Location: https://noteuniv.fr');
}
// Connection bdd
    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $sql_all_notes = "SELECT name_pdf, mini FROM global";
    $list_notes = $bdd->query($sql_all_notes);
    $totalNote = []; // tableau de toutes les notes de l'élève
    while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
        $sqlNote = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
        $myNote = $bdd->query($sqlNote);
        $noteEtudiant = $myNote->fetch();
        if ($noteEtudiant[0] > $note[1]) {
            array_push($totalNote, $noteEtudiant[0]); // push de ces notes dans le tableau pour moyenne
        }
    }
    $moyenne = array_sum($totalNote) / count($totalNote); // on fait la moyenne : Ensemble des notes du tableau / nbr de note
    $moyenne = round($moyenne, 2);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NoteUniv | Panel</title>
    <!-- CSS EXT-->
    <link rel="stylesheet" href="assets/css/flexboxgrid2.css" type="text/css">
    <!-- CSS PERSO-->
    <link rel="stylesheet" href="assets/css/stylePanel.css" type="text/css">
</head>

<body>
    <div class="row center-xs start-lg">
        <!-- ANCHOR CARD/ASIDE RIGHT-->
        <aside class="col-sm col-lg-3">
            <div class="row center-sm card">
                <div class="col-sm-12">
                    <img src="assets/images/logo_noteuniv_icon.svg" alt="" class="img-fluid img-ico">
                    <img src="assets/images/logo_noteuniv_text.svg" alt="" class="img-fluid img-txt">
                    <p class="as-etu">Etudiant</p>
                    <p>N°<?php echo $id_etu; ?></p>
                    <p class="as-small">Je suis actuellement en :</p>
                    <button class="btn-etu"><span class="tippy-note"
                            data-tippy-content="T'as bien fait, c'est les meilleurs ;)">MMI</span></button> <br>
                    <button class="btn-etu">SEMESTRE 1</button>
                    <p class="as-small">Ma moyenne générale est :</p>
                    <button class="btn-moy"><?php echo $moyenne; ?> / 20</button>
                    <?php
                    if ($moyenne >= 15) {
                        echo '<p class="green">MAIS T\'ES QUEL SORTE DE DIEU AU JUSTE ?!</p>';
                    }else if ($moyenne >= 13) {
                        echo '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >=10) {
                        echo '<p class="orange">ALLEZZZ ! ça passe tout juste ;)</p>';
                    }else {
                        echo '<p class="red">Merde, c\'est chaud wlh :(</p>';
                    }
                    ?>
                    <p class="btn-logout"><a href="panel.php">Récapitulatif</a></p>
                    <p class="btn-logout"><a href="https://noteuniv.fr/test/">Se déconnecter</a></p>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-8 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">El Classement de la muerté </h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Classement</h1>

                <!-- ANCHOR Bandeau de l'UE 1 uniquement PC/Tablette -->
                <div class="row ue-tab hidden-xs">
                    <div class="col-sm-3 ue-nbr">
                        <p>Rang</p>
                    </div>
                    <div class="col-sm-9">
                        <div class="row note-overlay center-sm">
                            <div class="col-sm">
                                <p>Moyenne</p>
                            </div>
                            <div class="col-sm">
                                <p>Etudiant</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANCHOR Notes -->
                <?php

$sqlMoy = $bdd->query("SELECT num_etu, note FROM ranking ORDER BY note DESC");
$i = 1;
while ($moy = $sqlMoy->fetch()) {
//  echo "$i : $moy[0] -> $moy[1] <br>";
 
?>

                <article class="row all-note">
                    <div class="col-sm-3 matiere first-xs">
                        <p class='titre-mobile'><?php
                         if ($i<4) {
                             echo '<span class="green">'.$i.'</span>';
                         } else {
                             echo $i;
                         }
                         ?></p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-9 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs">
                                <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                    <?php 
                                    if ($moy[1] == $moyenne) {
                                        echo '<span class="green tippy-note" data-tippy-content="C\'est toi gros ! J\'espère que sa te va :)">'.$moy[1].'</span>';
                                    } else {
                                        echo $moy[1];
                                    }
                                    ?> </p>
                            </div>
                            <div class="col-sm col-xs">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Etudiant<br><br></span>
                                    <?php echo $moy[0]; ?></p>
                            </div>
                        </div>
                    </div>
                </article>
                <?php
                $i++;
            }
 ?>
            </section>
        </div>
    </div>

    </div>
    <!-- <footer>
        <div class="row center-xs">
            <div class="col-xs-12" style='border-top: 1px solid black;'>
                <p class="as-small">Made with :heart: By Erosya</p>
            </div>
            
        </div>
    </footer> -->
    <!-- SCRIPT EXT -->
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <!-- SCRIPT PERSO -->
    <script src="assets/js/appLast.js"></script>
    <!-- BLOC NOTE   -->
</body>

</html>