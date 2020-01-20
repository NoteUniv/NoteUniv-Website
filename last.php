<?php
session_start();
// Dépendances
require "vendor/autoload.php";

// Changement de semestre
if (empty($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    setcookie("semestre", "1", strtotime('+360 days'));
    $semestre = 1;
} else {
    $semestre = htmlspecialchars($_COOKIE['semestre']);
}

// MMI-1 Accès uniquement au S1/S2
if (isset($_GET['change']) && $semestre == 1) {
    setcookie("semestre", "2", strtotime('+360 days'));
    $semestre = 2;
} elseif (isset($_GET['change']) && $semestre == 2) {
    setcookie("semestre", "1", strtotime('+360 days'));
    $semestre = 1;
}
// MMI-2 = Accès uniquement au S3/S4 
if (isset($_GET['change']) && $semestre == 3) {
    setcookie("semestre", "4", strtotime('+360 days'));
    $semestre = 4;
} elseif (isset($_GET['change']) && $semestre == 4) {
    setcookie("semestre", "3", strtotime('+360 days'));
    $semestre = 3;
}

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
    $bdd->exec('SET NAMES utf8');
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Récupération Numéro Étudiant du formulaire
if ((!empty($_POST["numEtu"]) && is_numeric($_POST["numEtu"]))) {
    $id_etu = htmlspecialchars($_POST["numEtu"]);
    $_SESSION['id_etu'] = $id_etu;
} else if (!empty($_SESSION['id_etu']) && is_numeric($_SESSION['id_etu'])) {
    $id_etu = $_SESSION['id_etu'];
} else {
    header('Location: https://noteuniv.fr');
}
// $id_etu = 21901533;
// $_SESSION['id_etu'] = $id_etu;

// Include
include "assets/include/moy.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="title" content="Noteuniv, IUT Haguenau">
    <meta name="description" content="Retrouvez facilement vos note de l'iut de haguenau grâce à Noteuniv !">
    <meta name="keywords" content="noteuniv, haguenau, note iut haguenau, emploi du temps mmi, note mmi, noteuniv mmi">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="15 days">
    <meta name="author" content="Ynohtna, Quentium">
    <meta name="theme-color" content="#110133">
    <title>NoteUniv | Panel</title>
    <!-- FAVICON  -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#110133">
    <meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#110133">
    <!-- CSS EXT-->
    <link rel="stylesheet" href="assets/css/flexboxgrid2.css" type="text/css">
    <!-- CSS PERSO-->
    <link rel="stylesheet" href="assets/css/stylePanel.css" type="text/css">
    <!-- Cookie  -->
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="0df23692-fee1-4280-97ef-7c0506f2621d" data-blockingmode="auto" type="text/javascript"></script>
    <!-- Matomo -->
    <script type="text/javascript">
        var _paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
        _paq.push(["setCookieDomain", "*.noteuniv.fr"]);
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u = "//dev.noteuniv.fr/piwik/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '2']);
            var d = document,
                g = d.createElement('script'),
                s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.defer = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript>
        <p><img src="//dev.noteuniv.fr/piwik/matomo.php?idsite=2&amp;rec=1" style="border:0;" alt="" /></p>
    </noscript>
    <!-- End Matomo Code -->
</head>

<body>
    <div class="row center-xs start-lg">
        <!-- ANCHOR CARD/ASIDE RIGHT-->
        <aside class="col-sm col-lg-3">
            <div class="row center-sm card">
                <div class="col-sm-12">
                    <img src="assets/images/noteuniv_logo.svg" alt="" class="img-fluid img-ico">
                    <img src="assets/images/noteuniv_text.svg" alt="" class="img-fluid img-txt">
                    <p class="as-etu">Etudiant</p>
                    <p>N°<?php echo $id_etu; ?></p>
                    <p class="as-small">Je suis actuellement en :</p>
                    <button class="btn-etu"><span class="tippy-note" data-tippy-content="T'as bien fait, c'est les meilleurs ;)">MMI</span></button> <br>
                    <button class="btn-etu"> <span class="tippy-note" data-tippy-content="Changement de Semestre"> <a href='?change=true'>SEMESTRE
                                <?php echo $semestre; ?></a></span></button>
                    <p class="as-small">Ma moyenne générale est :</p>
                    <button class="btn-moy"><span class="tippy-note" data-tippy-content="<a href='ranking.php'>Besoin de voir ta grandeur ?</a>"><?php echo $moyenne; ?>
                            / 20</span></button>
                    <?php
                    if ($moyenne >= 15) { // Moyenne sup ou égal à 15
                        echo '<p class="green">Un Dieu.</p>';
                    } else if ($moyenne >= 13) { // Sup/eg à 13
                        echo '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >= 10) { // sup/eg à 10
                        echo '<p class="orange">ALLEZZZ ! ça passe !</p>';
                    } else { // en dessous de 10
                        echo '<p class="red">aïe, trql on se motive!</p>';
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
                                <p>Coef</p>
                            </div>
                            <div class="col-sm-7">
                                <p>Nom du devoir</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANCHOR Notes -->
                <?php
                switch ($semestre) { // en fct du semestre on fait une requete
                    case '1':
                        $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, type_note, type_epreuve, note_semester FROM global_s1 ORDER BY note_date DESC";
                        break;
                    case '2':
                        $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, type_note, type_epreuve, note_semester FROM global_s2 ORDER BY note_date DESC";
                        break;
                    case '3':
                        $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, type_note, type_epreuve, note_semester ORDER BY note_date DESC";
                        break;
                    case '4':
                        $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, type_note, type_epreuve, note_semester FROM global_s4 ORDER BY note_date DESC";
                        break;
                    default:
                        $sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, type_note, type_epreuve, note_semester FROM global_s1 ORDER BY note_date DESC";
                        break;
                }

                $list_notes = $bdd->query($sql_all_notes);
                while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
                    $name = str_replace("_", " ", $note['name_devoir']);
                    $pdf = $note['name_pdf'];
                    $noteMoyenne = round($note['moy'], 2);
                    $mini = $note['mini'];
                    $maxi = $note['maxi'];
                    $coeff = $note['note_coeff'];
                    $matiere = $note['note_code'];
                    $type = $note['type_note'];
                    $epreuve = $note['type_epreuve'];
                    $sqlNote = "SELECT note_etu FROM $note[name_pdf] WHERE id_etu = $id_etu";
                    $myNote = $bdd->query($sqlNote);
                    $noteEtu = $myNote->fetch();

                ?>

                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p class='titre-mobile'><?php
                                                    if (preg_match("/AV1?/", $matiere)) { // Ester eggs
                                                    ?>
                                    <span class="tippy-note" data-tippy-content="<a href='https://youtu.be/CobknKR0t6k' target='_BLANK' class'green'>Tu veux voir un vrai truc en AV ? Clique !</a>"><?php echo $matiere ?></span>
                                <?php

                                                    } else if ($type !== "Note unique" && $type !== "Moyenne de notes (+M)") {
                                                        echo '<span class="orange tippy-note" data-tippy-content="Note Intermédiaire. Pas prise en compte dans la moyenne. Uniquement pour affichage">' . $matiere . '</span>';
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
                                        if ($noteEtu[0] > 21) { // 100 = abs
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
                                        <?php if ($type == "Moyenne de notes (+M)") {
                                            echo "Moyenne des notes intermédiaires " . $epreuve;
                                        } else {
                                            echo $name;
                                        } ?></p>
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
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ By <a href="https://erosya.fr" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr">Nous
                        contacter</span> | <a href="terms.html">Mention légales</a></p>
            </div>

        </div>
        <!-- SCRIPT EXT -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/tippy-bundle.iife.min.js"></script>
        <!-- SCRIPT PERSO -->
        <script src="assets/js/app.js"></script>
    </footer>
</body>

</html>