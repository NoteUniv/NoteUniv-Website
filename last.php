<?php
session_start();
// Dépendances
require "vendor/autoload.php";

// Changement de semestre
if (!isset($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    setcookie("semestre", "1", strtotime('+360 days'));
} else {
    $semestre = htmlspecialchars($_COOKIE['semestre']);
}

if (isset($_GET['change'])) {
    // MMI-1 Accès uniquement au S1/S2
    if ($semestre == 1) {
        setcookie("semestre", "2", strtotime('+360 days'));
        $semestre = 2;
    } elseif ($semestre == 2) {
        setcookie("semestre", "1", strtotime('+360 days'));
        $semestre = 1;
    }
    // MMI-2 Accès uniquement au S3/S4
    if ($semestre == 3) {
        setcookie("semestre", "4", strtotime('+360 days'));
        $semestre = 4;
    } elseif ($semestre == 4) {
        setcookie("semestre", "3", strtotime('+360 days'));
        $semestre = 3;
    }
    // Modification de l'URL si paramètre GET
    echo '<script>
        window.history.replaceState({}, document.title, location.pathname);
    </script>';
}

// Récupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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
    echo "Connection failed: " . $e->getMessage();
}

// Récupération Numéro Étudiant du formulaire
if ((!empty($_POST["numEtu"]) && is_numeric($_POST["numEtu"]))) {
    $id_etu = htmlspecialchars($_POST["numEtu"]);
    $_SESSION['id_etu'] = $id_etu;
} else if (!empty($_SESSION['id_etu']) && is_numeric($_SESSION['id_etu'])) {
    $id_etu = $_SESSION['id_etu'];
} else {
    header('Location: https://noteuniv.fr/');
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
    <meta name="description" content="Retrouvez plus facilement vos notes de l'IUT de Haguenau grâce à NoteUniv !">
    <meta name="keywords" content="noteuniv, haguenau, note iut haguenau, emploi du temps mmi, note mmi, noteuniv mmi">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="15 days">
    <meta name="author" content="Ynohtna, Quentium">
    <title>NoteUniv | Last</title>
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
                    <div class="logos">
                        <img src="assets/images/noteuniv_logo.svg" alt="Logo NoteUniv" class="img-fluid img-ico">
                        <img src="assets/images/noteuniv_text.svg" alt="Texte NoteUniv" class="img-fluid img-txt">
                    </div>
                    <p class="as-etu">Étudiant</p>
                    <p>N°<?= $id_etu; ?></p>
                    <p class="as-small">Je suis actuellement en :</p>
                    <span class="btn btn-etu">
                        <span class="tippy-note" data-tippy-content="T'as bien fait, c'est les meilleurs ;)">MMI</span>
                    </span>
                    <br>
                    <a href="?change=true">
                        <span class="btn btn-etu">
                            <span class="tippy-note" data-tippy-content="Changement de semestre">SEMESTRE <?= $semestre ?></span>
                        </span>
                    </a>
                    <p class="as-small">Ma moyenne générale est :</p>
                    <span class="btn btn-moy">
                        <span class="tippy-note" data-tippy-content="<a href='ranking.php'>Besoin de voir ta grandeur ?</a>"><?= $moyenne ?> / 20</span>
                    </span>
                    <?php
                    if ($moyenne >= 15) {
                        echo '<p class="green">Un Dieu !</p>';
                    } else if ($moyenne >= 13) {
                        echo '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >= 10) {
                        echo '<p class="orange">ALLEEEZZZ ! Ça passe !</p>';
                    } else {
                        echo '<p class="red">Aïe, trql on se motive !</p>';
                    }
                    ?>
                    <a href="edt.php"><span class="btn btn-logout">Emploi du temps</span></a>
                    <a href="panel.php"><span class="btn btn-logout">Récapitulatif</span></a>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <?php
                $nb_notes = $bdd->query("SELECT COUNT(*) FROM global_s$semestre")->fetchColumn();
                ?>
                <h1 class="hidden-xs hidden-sm">Mes dernières notes (<?= $nb_notes ?> au total)</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Mes dernière notes (<?= $nb_notes ?> au total)</h1>

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
                $sql_all_notes = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, type_note, type_exam, note_semester FROM global_s$semestre ORDER BY note_date_c DESC";
                $list_notes = $bdd->query($sql_all_notes);
                while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
                    $name = str_replace("_", " ", $note['name_note']);
                    $pdf = $note['name_pdf'];
                    $noteMoyenne = round($note['average'], 2);
                    $minimum = $note['minimum'];
                    $maximum = $note['maximum'];
                    $coeff = $note['note_coeff'];
                    $matiere = $note['note_code'];
                    $type = $note['type_note'];
                    $epreuve = $note['type_exam'];
                    $sqlNote = "SELECT note_etu FROM $note[name_pdf] WHERE id_etu = $id_etu";
                    $myNote = $bdd->query($sqlNote);
                    $noteEtu = $myNote->fetch();
                ?>

                    <?php
                    if ($type !== "Note unique" && $type !== "Moyenne de notes (+M)") {
                        echo '<article class="row all-note faded">';
                    } else {
                        echo '<article class="row all-note">';
                    }
                    ?>
                    <div class="col-sm-2 matiere first-xs">
                        <p class='titre-mobile'>
                            <?php
                            if (preg_match("/AV1?/", $matiere)) { // Ester eggs
                            ?>
                                <span class="tippy-note" data-tippy-content="<a href='https://youtu.be/CobknKR0t6k' target='_BLANK' class='green'>Tu veux voir un vrai truc en AV ? Clique !</a>"><?php echo $matiere ?></span>
                            <?php
                            } else if ($type !== "Note unique" && $type !== "Moyenne de notes (+M)") {
                                echo '<span class="grey">' . $matiere . '*</span>';
                            } else {
                                echo $matiere;
                            }
                            ?>
                        </p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs-6">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note<br><br></span>
                                    <?php
                                    if ($noteEtu[0] > 21) { // 100 = abs
                                        echo '<span class="orange">ABS</span>';
                                    } else {
                                        if ($noteEtu[0] < 10) {
                                            echo '<span class="red">' . $noteEtu[0] . '</span>';
                                        } elseif ($noteEtu[0] < $noteMoyenne) {
                                            echo '<span class="orange">' . $noteEtu[0] . '</span>';
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
                                    <?php echo $minimum; ?></p>
                            </div>
                            <div class="col-sm col-xs-6">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note Max<br><br></span>
                                    <?php echo $maximum; ?></p>
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
                <div class="row">
                    <div class="col-xs-12">
                        <p>*: Note Intermédiaire. Pas prise en compte dans la moyenne. Uniquement pour affichage</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    </div>
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ By <a href="https://erosya.fr" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr">Nous
                        contacter</button> | <a href="terms.html">Mentions légales</a></p>
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