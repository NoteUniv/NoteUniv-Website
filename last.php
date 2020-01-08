<?php
session_start();
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

// Récupération Numéro Etudiant du formulaire
if ((!empty($_POST["numEtu"]) && is_numeric($_POST["numEtu"]))) {
    $id_etu = htmlspecialchars($_POST["numEtu"]);
    $_SESSION['id_etu'] = $id_etu;
} else if (!empty($_SESSION['id_etu']) && is_numeric($_SESSION['id_etu'])) {
    $id_etu = $_SESSION['id_etu'];
} else {
    header('Location: https://noteuniv.fr');
}
include "assets/include/moy.php";
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
                    <button class="btn-etu"><span class="tippy-note" data-tippy-content="T'as bien fait, c'est les meilleurs ;)">MMI</span></button> <br>
                    <button class="btn-etu">SEMESTRE 1</button>
                    <p class="as-small"><span class="tippy-note" data-tippy-content="<a href='ranking.php'>Besoin de voir ta grandeur ?</a>">Ma moyenne générale est :</span></p>
                    <button class="btn-moy"><?php echo $moyenne; ?> / 20</button>
                    <?php
                    if ($moyenne >= 15) {
                        echo '<p class="green">MAIS T\'ES QUEL SORTE DE DIEU AU JUSTE ?!</p>';
                    } else if ($moyenne >= 13) {
                        echo '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >= 10) {
                        echo '<p class="orange">ALLEZZZ ! ça passe tout juste ;)</p>';
                    } else {
                        echo '<p class="red">Merde, c\'est chaud wlh :(</p>';
                    }
                    ?>
                    <p class="btn-logout"><a href="panel.php">Récapitulatif</a></p>
                    <p class="btn-logout"><a href="https://noteuniv.fr/test/">Se déconnecter</a></p>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">Mes dernières Notes </h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Mes dernière notes</h1>

                <!-- ANCHOR Bandeau de l'UE 1 uniquement PC/Tablette -->
                <div class="row ue-tab hidden-xs">
                    <div class="col-sm-2 ue-nbr">
                        <p>Matière</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="row note-overlay center-sm">
                            <div class="col-sm">
                                <p>Note</p>
                            </div>
                            <div class="col-sm">
                                <p>Moyenne</p>
                            </div>
                            <div class="col-sm">
                                <p>Note Min</p>
                            </div>
                            <div class="col-sm">
                                <p>Note Max</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row center-sm">
                            <div class="col-sm-5">
                                <p>Coeff</p>
                            </div>
                            <div class="col-sm-7">
                                <p>Nom du devoir</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANCHOR Notes -->
                <?php
                $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff FROM global ORDER BY note_date DESC";
                $list_notes = $bdd->query($sql_all_notes);
                while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
                    $name = utf8_encode($note['name_devoir']);
                    $pdf = $note['name_pdf'];
                    $noteMoyenne = round($note['moy'], 2);
                    $mini = $note['mini'];
                    $maxi = $note['maxi'];
                    $coeff = $note['note_coeff'];
                    $matiere = $note['note_code'];
                    $sqlNote = "SELECT note_etu FROM $note[name_pdf] WHERE id_etu = $id_etu";
                    $myNote = $bdd->query($sqlNote);
                    $noteEtu = $myNote->fetch();
                ?>

                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p class='titre-mobile'><?php
                                                    if (preg_match("/AV?/", $matiere)) {
                                                    ?>
                                    <span class="tippy-note" data-tippy-content="<a href='https://youtu.be/CobknKR0t6k' target='_BLANK' class'green'>Tu veux voir un vrai truc en AV ? Clique !</a>"><?php echo $matiere ?></span>
                                <?php
                                                    } else {
                                                        echo $matiere;
                                                    }
                                ?></p>
                        </div>
                        <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                        <div class="col-sm-6 last-xs initial-order-sm">
                            <div class="row center-sm note-par-matiere">
                                <div class="col-sm col-xs-6">
                                    <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note<br><br></span>
                                        <?php
                                        if ($noteEtu[0] < $mini) {
                                            echo '<span class="orange tippy-note" data-tippy-content="Hum, mais que c\'est il passé Billy ?">ABS</span>';
                                        } else {
                                            if ($noteEtu[0] < 10) {
                                                echo '<span class="red">' . $noteEtu[0] . '</span>';
                                            } elseif ($noteEtu[0] < $noteMoyenne) {
                                                echo '<span class="orange">' . $noteEtu[0] . '</span>';
                                            } elseif ($noteEtu[0] == 20) {
                                                echo '<span class="green tippy-note" data-tippy-content="MAIS TU ES UN DIEU BILLY !">' . $noteEtu[0] . '</span>';
                                            } else {
                                                echo '<span class="green">' . $noteEtu[0] . '</span>';
                                            }
                                        }
                                        ?> </p>
                                </div>
                                <div class="col-sm col-xs-6">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                        <?php echo $noteMoyenne; ?></p>
                                </div>
                                <div class="col-sm col-xs-6">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note Min<br><br></span>
                                        <?php echo $mini; ?></p>
                                </div>
                                <div class="col-sm col-xs-6">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note Max<br><br></span>
                                        <?php echo $maxi; ?></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row start-xs center-sm">
                                <div class="col-xs-12 col-sm-5 first-sm">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coeff: </span>
                                        <?php echo $coeff; ?>
                                    </p>
                                </div>
                                <div class="col-xs-12 col-sm-7 first-xs">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Nom du devoir: </span>
                                        <?php echo $name; ?></p>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
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
</body>

</html>