<?php
session_start();
// Dépendances
include_once("vendor/autoload.php");

// Changement de semestre
if (!isset($_COOKIE['semestre'])) {
    header('Location: ./');
} else {
    $semestre = htmlspecialchars($_COOKIE['semestre']);
}

if (isset($_GET['change'])) {
    // MMI-1 Accès uniquement au S1/S2
    if ($semestre == 's1') {
        setcookie("semestre", "s2", strtotime('+360 days'));
        $semestre = 's2';
    } elseif ($semestre == 's2') {
        setcookie("semestre", "s1", strtotime('+360 days'));
        $semestre = 's1';
    }
    // MMI-2 Accès uniquement au S3/S4
    if ($semestre == 's3') {
        setcookie("semestre", "s4", strtotime('+360 days'));
        $semestre = 's4';
    } elseif ($semestre == 's4') {
        setcookie("semestre", "s3", strtotime('+360 days'));
        $semestre = 's3';
    }
    // Modification de l'URL si paramètre GET
    echo '<script>
        window.history.replaceState({}, document.title, location.pathname);
    </script>';
}

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
}

// Récupération Numéro Étudiant du formulaire
if (!empty($_POST["numEtu"]) && is_numeric($_POST["numEtu"])) {
    $id_etu = htmlspecialchars($_POST["numEtu"]);
    $_SESSION['id_etu'] = $id_etu;
} else if (!empty($_SESSION['id_etu']) && is_numeric($_SESSION['id_etu'])) {
    $id_etu = $_SESSION['id_etu'];
} else {
    header('Location: ./');
}

// Set cookie ETU
if (!isset($_COOKIE['idEtuFirst'])) {
    setcookie("idEtuFirst", $id_etu, strtotime('+30 mins'));
}

// Include
include "assets/include/moy.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="title" content="NoteUniv, IUT Haguenau">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flexboxgrid2" type="text/css">
    <!-- CSS PERSO-->
    <link rel="stylesheet" href="assets/css/stylePanel.css" type="text/css">
    <!-- Cookie  -->
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="0df23692-fee1-4280-97ef-7c0506f2621d" data-blockingmode="auto" type="text/javascript"></script>
    <!-- Matomo -->
    <script type="text/javascript">
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
        _paq.push(["setCookieDomain", "*.noteuniv.fr"]);
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u = "//stats.noteuniv.fr/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d = document,
                g = d.createElement('script'),
                s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript>
        <p><img src="//stats.noteuniv.fr/matomo.php?idsite=1&amp;rec=1" style="border:0;" alt="" /></p>
    </noscript>
    <!-- End Matomo Code -->
</head>

<body>
    <div class="row center-xs start-lg">
        <!-- ANCHOR CARD/ASIDE RIGHT-->
        <aside class="col-sm col-lg-3">
            <div class="row center-sm card">
                <div class="col-sm-12">
                    <a href="./">
                        <div class="logos">
                            <img src="assets/images/noteuniv_logo.svg" alt="Logo NoteUniv" class="img-fluid img-ico">
                            <img src="assets/images/noteuniv_text.svg" alt="Texte NoteUniv" class="img-fluid img-txt">
                        </div>
                    </a>
                    <p class="as-etu">Étudiant</p>
                    <p>N°<?= $id_etu ?></p>
                    <p class="as-small">Je suis actuellement en :</p>
                    <span class="btn btn-etu">
                        <span class="tippy-note" data-tippy-content="T'as bien fait, c'est les meilleurs ;)"><?= $_COOKIE['promo'] ?></span>
                    </span>
                    <br>
                    <?php if ($_COOKIE['promo'] === 'MMI') { ?>
                        <a href="?change=true">
                            <span class="btn btn-etu">
                                <span class="tippy-note" data-tippy-content="Changement de semestre">SEMESTRE <?= $semestre[-1] ?></span>
                            </span>
                        </a>
                    <?php } ?>
                    <p class="as-small">Ma moyenne générale est :</p>
                    <?php
                    $moyenne = calcAverage($id_etu);
                    if ($id_etu === "1") {
                        echo '<p class="red">Toutes les notes sont aléatoires pour la démo !</p>';
                    }
                    if ($moyenne >= 15) {
                        echo '<span class="btn btn-green">';
                        $tmp = '<p class="green">Un Dieu !</p>';
                    } else if ($moyenne >= 13) {
                        echo '<span class="btn btn-green">';
                        $tmp = '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >= 10) {
                        echo '<span class="btn btn-orange">';
                        $tmp = '<p class="orange">ALLEEEZZZ ! Ça passe !</p>';
                    } else {
                        echo '<span class="btn btn-red">';
                        $tmp = '<p class="red">Aïe, trql on se motive !</p>';
                    }
                    echo '<span class="tippy-note" data-tippy-content="<a href=\'ranking.php\'>Besoin de voir ta grandeur ?</a>">' . $moyenne . ' / 20</span>';
                    echo '</span>';
                    echo $tmp;
                    ?>
                    <a href="edt.php"><span class="btn btn-logout">Emploi du temps</span></a>
                    <a href="panel.php"><span class="btn btn-logout">Récapitulatif</span></a>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <section class="note">
                <?php
                if ($notExists === true) include "assets/include/soon.php";
                $nb_notes = $bdd->query("SELECT COUNT(*) FROM global_$semestre")->fetchColumn();
                ?>
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">Mes dernières notes (<?= $nb_notes ?> au total)</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Mes dernières notes (<?= $nb_notes ?> au total)</h1>

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

                <?php
                $sql_all_notes = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, type_note, type_exam, note_semester FROM global_$semestre ORDER BY note_date_c DESC";
                $list_notes = $bdd->query($sql_all_notes);
                while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
                    $name = str_replace("_", " ", $note['name_note']);
                    $pdf = $note['name_pdf'];
                    $noteMoyenne = round($note['average'], 2);
                    $minimum = $note['minimum'];
                    $maximum = $note['maximum'];
                    $coeff = $note['note_coeff'];
                    $subject = $note['note_code'];
                    $type = $note['type_note'];
                    $exam = $note['type_exam'];
                    $sqlNote = "SELECT note_etu FROM `$note[name_pdf]` WHERE id_etu = $id_etu";
                    $myNote = $bdd->query($sqlNote);
                    $noteEtu = $myNote->fetch();
                    if ($id_etu === "1") {
                        $noteEtu[0] = random_int(10, 20);
                    }
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
                            if (preg_match("/AV\d?/", $subject)) { // Easter egg
                            ?>
                                <span class="tippy-note" data-tippy-content="<a href='https://youtu.be/CobknKR0t6k' target='_BLANK' class='green'>Tu veux voir un vrai truc en AV ? Clique !</a>"><?= $subject ?></span>
                            <?php
                            } else if ($type !== "Note unique" && $type !== "Moyenne de notes (+M)") {
                                echo '<span class="grey">' . $subject . '*</span>';
                            } else if ($noteEtu[0] == 100) {
                                echo '<span class="red">' . $subject . '</span>';
                            } else {
                                echo $subject;
                            }
                            ?>
                        </p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs-6">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note<br><br></span>
                                    <?php
                                    if ($noteEtu[0] == 100) { // 100 = abs
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
                                    ?>
                                </p>
                            </div>
                            <div class="col-sm col-xs-6">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                    <?= $noteMoyenne ?>
                                </p>
                            </div>
                            <div class="col-sm col-xs-6">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note Min<br><br></span>
                                    <?= $minimum ?>
                                </p>
                            </div>
                            <div class="col-sm col-xs-6">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note Max<br><br></span>
                                    <?= $maximum ?>
                                </p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row start-xs center-sm">
                            <div class="col-xs-12 col-sm-5 first-sm">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Coeff :</span>
                                    <?= $coeff ?>
                                </p>
                            </div>
                            <div class="col-xs-12 col-sm-7 first-xs">
                                <p>
                                    <span class="hidden-sm hidden-md hidden-lg hidden-xl">Nom du devoir :</span>
                                    <?php
                                    if ($type == "Moyenne de notes (+M)") {
                                        echo "Moyenne des notes intermédiaires " . $exam;
                                    } else {
                                        echo $name;
                                    } ?>
                                </p>
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
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ by <a href="https://erosya.fr" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr / pro@quentium.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
            </div>
        </div>
        <!-- SCRIPT EXT -->
        <script src="https://unpkg.com/@popperjs/core"></script>
        <script src="https://unpkg.com/tippy.js"></script>
        <!-- SCRIPT PERSO -->
        <script src="assets/js/app.js"></script>
    </footer>
</body>

</html>