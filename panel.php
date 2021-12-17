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
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
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
                    <a href="last.php"><span class="btn btn-logout">Dernières notes</span></a>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <section class="note">
                <?php
                if ($notExists === true) include "assets/include/soon.php";
                ?>
                <h1 class="hidden-xs hidden-sm">Le récapitulatif de mes notes</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Mon récap'</h1>

                <div class="row center-xs hidden-sm hidden-md hidden-lg hidden-xl nav">
                    <?php
                    foreach (array_keys($UESubjects) as $ue) {
                    ?>
                        <div class="col-xs-3">
                            <p><a href="#<?= $ue ?>"><?= $ue ?></a></p>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="col-xs-3">
                        <p><a href="#result"><?= strtoupper($semestre) ?></a></p>
                    </div>
                </div>

                <?php
                foreach (array_keys($UESubjects) as $ue) {
                ?>
                    <h2 class="hidden-sm hidden-md hidden-lg hidden-xl" id="<?= $ue ?>"><?= $ue ?></h2>

                    <div class="row ue-tab hidden-xs">
                        <div class="col-sm-2 ue-nbr">
                            <p><?= $ue ?></p>
                        </div>
                        <div class="col-sm-6">
                            <div class="row note-overlay center-sm">
                                <div class="col-sm">
                                    <p>Note 1</p>
                                </div>
                                <div class="col-sm">
                                    <p>Note 2</p>
                                </div>
                                <div class="col-sm">
                                    <p>Note 3</p>
                                </div>
                                <div class="col-sm">
                                    <p>Note 4</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row center-sm">
                                <div class="col-sm">
                                    <p>Coeff</p>
                                </div>
                                <div class="col-sm">
                                    <p>Moyenne</p>
                                </div>
                                <div class="col-sm">
                                    <p>Notes*</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $averageSubjects = [];
                    foreach ($UESubjects[$ue] as $key => $value) {
                        $sqlSem = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, name_teacher, type_note, note_semester, note_total, median, variance, deviation, type_exam FROM global_$semestre WHERE note_code = '$value' AND type_note NOT LIKE '%intermédiaire%' ORDER BY note_date_c, id DESC";
                        $sqlPDF = $bdd->query($sqlSem);
                    ?>
                        <article class="row all-note">
                            <div class="col-sm-2 matiere first-xs">
                                <p><span><?= $value ?></span></p>
                            </div>
                            <div class="col-sm-6 last-xs initial-order-sm">
                                <div class="row center-sm note-par-matiere">
                                    <?php
                                    $i = 1; // nombre de note
                                    $avgSubject = []; // Moyenne de chaque matière
                                    $n = 0; // nombre de note compté dans la moyenne

                                    while ($infoNote = $sqlPDF->fetch()) {
                                        $name = $infoNote['name_note'];
                                        $pdf = $infoNote['name_pdf'];
                                        $noteMoyenne = round($infoNote['average'], 2);
                                        $minimum = $infoNote['minimum'];
                                        $maximum = $infoNote['maximum'];
                                        $coeff = $infoNote['note_coeff'];
                                        $type = $infoNote['type_note'];
                                        $date = $infoNote['note_date_c'];
                                        $ens = $infoNote['name_teacher'];
                                        $totalNote = $infoNote['note_total'];
                                        $median = $infoNote['median'];
                                        $variance = round($infoNote['variance'], 2);
                                        $deviation = round($infoNote['deviation'], 2);
                                        $subject = $infoNote['note_code'];
                                        $typeExam = $infoNote['type_exam'];
                                        $myNote = $bdd->query("SELECT note_etu FROM `$infoNote[name_pdf]` WHERE id_etu = $id_etu");
                                        $noteEtu = $myNote->fetch();
                                        if ($id_etu === "1") {
                                            $noteEtu[0] = random_int(10, 20);
                                        }
                                        if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
                                            array_push($avgSubject, $noteEtu[0]);
                                            $n++;
                                            $coeffSubject = $coeff;
                                    ?>
                                            <div class="col-sm col-xs-6">
                                                <a href="javascript:void(0);" data-template="<?= $subject . $i ?>" class="tippy-note">
                                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note <?= $i ?><br></span>
                                                        <?php
                                                        if ($noteEtu[0] == 100) { // si abs
                                                            echo '<span class="orange tippy-note" data-tippy-content="Hum, mais que s&apos;est il passé Billy ?">ABS</span>';
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
                                                        ?>
                                                    </p>
                                                </a>
                                            </div>
                                            <div class="popup">
                                                <div id="<?= $subject . $i ?>">
                                                    <div class="user-note">
                                                        <h2 class="note-header">Détails de la note</h2>
                                                        <p class="b">Nom du devoir / Module :</p>
                                                        <p><?php if ($type == "Moyenne de notes (+M)") {
                                                                echo "Moyenne des notes intermédiaires " . $typeExam;
                                                            } else {
                                                                echo $name;
                                                            } ?></p>
                                                        <p class="b">Enseignant :</p>
                                                        <p><?= $ens ?></p>
                                                        <p class="b">Date de l'épreuve :</p>
                                                        <p><?= $date ?></p>
                                                        <p class="b">Type de note :</p>
                                                        <p><?= $type ?></p>
                                                        <p class="b">Type d'épreuve : </p>
                                                        <p><?= $typeExam ?></p>
                                                        <h2 class="note-header">Et ma promo alors ?</h2>
                                                    </div>

                                                    <div class="promo-note">
                                                        <div class="row center-xs">
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Moyenne</span><br><?= $noteMoyenne ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Médiane</span><br><?= $median ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Min</span><br><?= $minimum ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Max</span><br><?= $maximum ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row center-xs separation-note">
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b"></span>Total notes<br><?= $totalNote ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Variance</span><br><?= $variance ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-xs-6">
                                                                <div class="btn-etu">
                                                                    <p>
                                                                        <span class="b">Écart type</span><br><?= $deviation ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                            $i++;
                                        }
                                    }

                                    while ($i < 5) {
                                        // Si pas d'autre note on comble avec un "/"
                                        ?>
                                        <div class="col-sm col-xs-6">
                                            <p>
                                                <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note <?= $i ?><br></span>
                                                /
                                            </p>
                                        </div>
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="row center-xs">
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="hidden-sm hidden-md hidden-lg hidden-xl">Coeff :</span>
                                            <?= $coeff ?>
                                        </p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne :</span>
                                            <?php
                                            if (count($avgSubject) == 0) {
                                                $moyenneMat = 0;
                                                echo "/";
                                                $coeffSubject = 0;
                                            } else {
                                                $moyenneMat = round(array_sum($avgSubject) / count($avgSubject), 3);
                                                echo $moyenneMat;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="col-xs-4">
                                        <p>
                                            <span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes :</span>
                                            <?= $n ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </section>

            <section class="note">
                <div class="row resume-tab around-sm hidden-xs">
                    <div class="col-sm-1 center-sm"></div>
                    <div class="col-sm-2 center-sm btn-etu">
                        <p>Moyenne sur 20</p>
                    </div>
                    <div class="col-sm-2 center-sm btn-etu">
                        <p>Résultats</p>
                    </div>
                </div>

                <h1 class="hidden-sm hidden-md hidden-lg hidden-xl" id="result">Résultats</h1>

                <?php
                $avgPerUE = calcAverage($id_etu, true);
                $semestreValid = array_filter($avgPerUE, function ($value) {
                    return $value[0] > 8;
                });
                foreach (array_keys($UESubjects) as $ue) {
                    $UEAvg = $avgPerUE[$ue][0];
                ?>
                    <article class="row all-note around-sm sem">
                        <div class="col-sm-1">
                            <h2 class="hidden-sm hidden-md hidden-lg hidden-xl"><?= $ue ?></h2>
                            <p><span class="hidden-xs"><?= $ue ?></span></p>
                        </div>
                        <div class="col-sm-2 center-sm btn-green">
                            <p>
                                <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20<br></span>
                                <?= $UEAvg ?>
                            </p>

                        </div>
                        <?php
                        if ($UEAvg >= 8) {
                        ?>
                            <div class="col-sm-2 center-sm btn-green mr-14">
                                <p>UE Validé</p>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="col-sm-2 center-sm btn-orange mr-14">
                                <p>UE Échoué</p>
                            </div>
                        <?php
                        }
                        ?>
                    </article>
                <?php
                }
                ?>

                <article class="row all-note around-sm sem">
                    <div class="col-sm-1">
                        <?php
                        if ($_COOKIE['promo'] === 'MMI') {
                            echo '<h2 class="hidden-sm hidden-md hidden-lg hidden-xl">Semestre ' . $semestre[-1] . '</h2>';
                        } else {
                            echo '<h2 class="hidden-sm hidden-md hidden-lg hidden-xl">' . strtoupper($semestre) . '</h2>';
                        }
                        ?>
                        <p><span class="hidden-xs"><?= strtoupper($semestre) ?></span></p>
                    </div>
                    <div class="col-sm-2 center-sm btn-green">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20</span>
                            <?= $moyenne ?>
                        </p>
                    </div>
                    <?php
                    if ($moyenne >= 10 && $semestreValid === $avgPerUE) {
                    ?>
                        <div class="col-sm-2 center-sm btn-green mr-14">
                            <p>Semestre Validé </p>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-sm-2 center-sm btn-red mr-14">
                            <p>Semestre Échoué</p>
                        </div>
                    <?php
                    }
                    ?>

                </article>
            </section>
            <p>*: Notes comptant dans la moyenne</p>
        </div>
    </div>
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ by <a href="https://oserya.fr/" target="_BLANK">Oserya</a> | <span class="tippy-note" data-tippy-content="QuentiumYT#0207 | Ynohtna#0001 / pro@quentium.fr | contact@anthony-adam.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
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