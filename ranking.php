<?php
session_start();
require "vendor/autoload.php";
// Récupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$servername = getenv('SERVERNAME');
$dbname = getenv('DBNAME');
$username = getenv('USER');
$password = getenv('PASSWORD');

// Connection bdd
try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//Récupération Numéro Etudiant du formulaire
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
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
                    <p class="as-small">Ma moyenne générale est :</p>
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
                    <p class="btn-logout"><a href="https://noteuniv.fr/">Se déconnecter</a></p>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">El Classement de la muerté </h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Classement</h1>

                <!-- ANCHOR Bandeau de l'UE 1 uniquement PC/Tablette -->
                <div class="row ue-tab hidden-xs">
                    <div class="col-sm-2 ue-nbr">
                        <p>Rang</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="row note-overlay center-sm">
                            <div class="col-sm">
                                <p>Moyenne</p>
                            </div>
                            <div class="col-sm">
                                <p>Etudiant</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 center-sm">
                        <p>Récompense</p>
                    </div>
                </div>

                <!-- ANCHOR Notes -->
                <?php

                $sqlMoy = $bdd->query("SELECT id_etu, moy_etu FROM ranking ORDER BY moy_etu DESC");
                $i = 1;
                while ($moy = $sqlMoy->fetch()) {
                    //  echo "$i : $moy[0] -> $moy[1] <br>";

                ?>

                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p class='titre-mobile'><?php
                                                    if ($i < 4) {
                                                        if ($i == 1) {
                                                            echo '<span class="green tippy-note" data-tippy-content="Mieux que les TOP1 Fortnite non ?">' . $i . '</span>';
                                                        } else {
                                                            echo '<span class="green">' . $i . '</span>';
                                                        }
                                                    } else {
                                                        echo $i;
                                                    }
                                                    ?></p>
                        </div>
                        <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                        <div class="col-sm-6 last-xs initial-order-sm">
                            <div class="row center-sm note-par-matiere">
                                <div class="col-sm col-xs">
                                    <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                        <?php
                                        if ($moy[1] == $moyenne && $moy[0] == $id_etu) {
                                            echo '<span class="green tippy-note" data-tippy-content="C\'est toi gros ! J\'espère que ça te va :)">' . $moy[1] . '</span>';
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
                        <?php
                        switch ($i) {
                            case '1':
                                print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>1000 Erya</p></div>');
                                break;
                            case '2':
                                print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>500 Erya</p></div>');
                                break;
                            case '3':
                                print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>250 Erya</p></div>');
                                break;
                            case '4':
                                print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span><span class="tippy-note" data-tippy-content="CHECH">LE SEUM</span></p></div>');
                                break;
                            default:
                                print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>Aucune</p></div>');
                                break;
                        }
                        ?>
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
</body>

</html>