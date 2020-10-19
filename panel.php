<?php
session_start();
// Dépendances
include_once("vendor/autoload.php");

// Changement de semestre
if (!isset($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    header('Location: ./');
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
                    <?php
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
            <!-- ANCHOR NOTES -->
            <section class="note">
                <?php
                if ($notExists === true) include "assets/include/soon.php";
                ?>
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">Le récapitulatif de mes notes</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Mon récap'</h1>

                <!-- Menu de navigation pour mobile  -->
                <div class="row center-xs hidden-sm hidden-md hidden-lg hidden-xl nav">
                    <div class="col-xs-3">
                        <p><a href="#ue1">UE1</a></p>
                    </div>
                    <div class="col-xs-3">
                        <p><a href="#ue2">UE2</a></p>
                    </div>
                    <div class="col-xs-3">
                        <p><a href="#resultat">S1</a></p>
                    </div>
                </div>
                <!-- Affichage de UE1 uniquement pour mobile, car ils n'ont pas de bandeau  -->
                <h2 class="hidden-sm hidden-md hidden-lg hidden-xl" id="ue1">UE 1</h2>

                <!-- ANCHOR Bandeau de l'UE 1 uniquement PC/Tablette -->
                <div class="row ue-tab hidden-xs">
                    <div class="col-sm-2 ue-nbr">
                        <p>UE1</p>
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
                $moyenneDesMatiere = [];
                foreach ($ue1 as $key => $value) {
                    $sqlSem = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, name_teacher, type_note, note_semester, note_total, median, variance, deviation, type_exam FROM global_s$semestre WHERE note_code = '$value' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
                    $ue1Sql = $bdd->query($sqlSem);
                ?>
                    <!-- ANCHOR Notes par matière 1 -->
                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p><span><?php echo $value; ?></span></p>
                        </div>
                        <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                        <div class="col-sm-6 last-xs initial-order-sm">
                            <div class="row center-sm note-par-matiere">
                                <?php
                                $i = 1; // nombre de note
                                $moyMatiere = []; // Moyenne de chaque matière
                                $n = 0; // nombre de note compté dans la moyenne

                                while ($infoNote = $ue1Sql->fetch()) {
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
                                    $matiere = $infoNote['note_code'];
                                    $typeEpreuve = $infoNote['type_exam'];
                                    $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
                                    $noteEtu = $myNote->fetch();
                                    if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
                                        array_push($moyMatiere, $noteEtu[0]);
                                        $n++;
                                        $coeffMatiere = $coeff;
                                ?>
                                        <div class="col-sm col-xs-6">
                                            <a href="javascript:void(0);" data-template="<?php echo $matiere . $i ?>" class="tippy-note">
                                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note <?php echo $i; ?><br></span>
                                                    <?php
                                                    if ($noteEtu[0] > 21) { // si abs
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
                                                    ?>
                                                </p>
                                            </a>
                                        </div>
                                        <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE1 -->
                                        <div class="popup">
                                            <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                                            <div id="<?php echo $matiere . $i; ?>">
                                                <div class="user-note">
                                                    <h2 class="note-header">Détails de la note</h2>
                                                    <p class="b">Nom du devoir / Module :</p>
                                                    <p><?php if ($type == "Moyenne de notes (+M)") {
                                                            echo "Moyenne des notes intermédiaires " . $typeEpreuve;
                                                        } else {
                                                            echo $name;
                                                        } ?></p>
                                                    <p class="b">Enseignant :</p>
                                                    <p><?php echo $ens; ?></p>
                                                    <p class="b">Date de l'épreuve :</p>
                                                    <p><?php echo $date; ?></p>
                                                    <p class="b">Type de note :</p>
                                                    <p><?php echo $type; ?></p>
                                                    <p class="b">Type d'épreuve : </p>
                                                    <p><?php echo $typeEpreuve; ?></p>
                                                    <h2 class="note-header">Et ma promo alors ?</h2>
                                                </div>

                                                <div class="promo-note">
                                                    <div class="row center-xs">
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Moyenne</span><br><?php echo $noteMoyenne; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Médiane</span><br><?php echo $median; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Min</span><br><?php echo $minimum; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Max</span><br><?php echo $maximum; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row center-xs separation-note">
                                                        <div class="col-sm-6 col-xs-12">
                                                            <div class="btn-etu">
                                                                <p> <span class="b"></span>Total notes<br><?php echo $totalNote; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Variance</span><br><?php echo $variance; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Ecart type</span><br><?php echo $deviation; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin intégration note modales  -->
                                    <?php
                                        $i++;
                                    };
                                }

                                while ($i < 5) { // Si pas d'autre note on comble avec un "/" 
                                    ?>
                                    <div class="col-sm col-xs-6">
                                        <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note
                                                <?php echo $i; ?><br></span>
                                            / </p>
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
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coeff :</span>
                                        <?php
                                        echo $coeff; ?></p>
                                </div>
                                <div class="col-xs-4">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne :</span>
                                        <?php
                                        if (count($moyMatiere) == 0) {
                                            $moyenneMat = 0;
                                            echo "/";
                                            $coeffMatiere = 0;
                                        } else {
                                            $moyenneMat = round(array_sum($moyMatiere) / count($moyMatiere), 3);
                                            echo $moyenneMat;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-xs-4">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> <?php echo $n; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
                    array_push($moyenneDesMatiere, ['moyMat' => $moyenneMat, 'coeff' => $coeffMatiere]);
                }
                $moyUe1 = 0;
                $coeffUe1 = 0;
                for ($i = 0; $i < count($moyenneDesMatiere); $i++) {
                    $moyUe1 += $moyenneDesMatiere[$i]['moyMat'] * $moyenneDesMatiere[$i]['coeff'];
                    $coeffUe1 += $moyenneDesMatiere[$i]['coeff'];
                }
                if ($coeffUe1 === 0) {
                    $moyUe1 = 0;
                } else {
                    $moyUe1 /= $coeffUe1;
                }
                ?>

                <!-- ANCHOR Bandeau de l'UE 2 uniquement pc/tablette-->
                <div class="row ue-tab hidden-xs">
                    <div class="col-sm-2 ue-nbr">
                        <p>UE2</p>
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

                <!-- Affichage de l'ue2 uniquement sur mobile car pas de bandeau  -->
                <h2 class="hidden-sm hidden-md hidden-lg hidden-xl" id="ue2">UE 2</h2>

                <!-- ANCHOR Notes par matière 2 -->
                <?php
                $moyenneDesMatiere = [];
                foreach ($ue2 as $key => $value) {
                    $sqlSem = "SELECT name_note, name_pdf, note_date_c, average, minimum, maximum, note_code, note_coeff, name_teacher, type_note, note_semester, note_total, median, variance, deviation, type_exam FROM global_s$semestre WHERE note_code = '$value' AND type_note != 'Note intermédiaire que pour affichage' ORDER BY note_date_c, id DESC";
                    $ue1Sql = $bdd->query($sqlSem);
                ?>
                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p><span><?php echo $value; ?></span></p>
                        </div>
                        <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                        <div class="col-sm-6 last-xs initial-order-sm">
                            <div class="row center-sm note-par-matiere">
                                <?php
                                $i = 1; // nombre de note
                                $moyMatiere = []; // Moyenne de chaque matière
                                $n = 0; // nombre de note compté dans la moyenne
                                while ($infoNote = $ue1Sql->fetch()) {
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
                                    $matiere = $infoNote['note_code'];
                                    $typeEpreuve = $infoNote['type_exam'];
                                    $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
                                    $noteEtu = $myNote->fetch();
                                    if ($noteEtu[0] < 21) { // Si pas abs et pas note intermédiaire on le compte
                                        array_push($moyMatiere, $noteEtu[0]);
                                        $n++;
                                        $coeffMatiere = $coeff;

                                ?>
                                        <div class="col-sm col-xs-6">
                                            <a href="javascript:void(0);" data-template="<?php echo $matiere . $i ?>" class="tippy-note">
                                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note <?php echo $i; ?><br></span>
                                                    <?php
                                                    if ($noteEtu[0] > 21) { // si pas abs
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
                                                    ?>
                                                </p>
                                            </a>
                                        </div>
                                        <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE2 -->
                                        <div class="popup">
                                            <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                                            <div id="<?php echo $matiere . $i; ?>">
                                                <div class="user-note">
                                                    <h2 class="note-header">Détails de la note</h2>
                                                    <p class="b">Nom du devoir / Module :</p>
                                                    <p><?php if ($type == "Moyenne de notes (+M)") {
                                                            echo "Moyenne des notes intérmédiaires " . $typeEpreuve;
                                                        } else {
                                                            echo $name;
                                                        } ?></p>
                                                    <p class="b">Enseignant :</p>
                                                    <p><?php echo $ens; ?></p>
                                                    <p class="b">Date de l'épreuve :</p>
                                                    <p><?php echo $date; ?></p>
                                                    <p class="b">Type de note :</p>
                                                    <p><?php echo $type; ?></p>
                                                    <p class="b">Type d'épreuve : </p>
                                                    <p><?php echo $typeEpreuve; ?></p>
                                                    <h2 class="note-header">Et ma promo alors ?</h2>
                                                </div>

                                                <div class="promo-note">
                                                    <div class="row center-xs">
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Moyenne</span><br><?php echo $noteMoyenne; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Mediane</span><br><?php echo $median; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Min</span><br><?php echo $minimum; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Max</span><br><?php echo $maximum; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row center-xs separation-note">
                                                        <div class="col-sm-6 col-xs-12">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Total Notes</span><br><?php echo $totalNote; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Variance</span><span><?php echo $variance; ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-6">
                                                            <div class="btn-etu">
                                                                <p> <span class="b">Ecart type</span><br><?php echo $deviation; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin intégration note modales  -->
                                    <?php
                                        $i++;
                                    };
                                }
                                while ($i < 5) { // Si pas d'autre note on comble avec un "/" 
                                    ?>
                                    <div class="col-sm col-xs-6">
                                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Note<?php echo $i; ?><br></span> / </p>
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
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coeff :</span>
                                        <?php echo $coeff; ?></p>
                                </div>
                                <div class="col-xs-4">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl ">Moyenne :</span>
                                        <?php
                                        if (count($moyMatiere) == 0) {
                                            $moyenneMat = 0;
                                            $coeffMatiere = 0;
                                            echo "/";
                                        } else {
                                            $moyenneMat = round(array_sum($moyMatiere) / count($moyMatiere), 3);
                                            echo $moyenneMat;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="col-xs-4">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> <?php echo $n; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
                    array_push($moyenneDesMatiere, ['moyMat' => $moyenneMat, 'coeff' => $coeffMatiere]);
                }
                $moyUe2 = 0;
                $coeffUe2 = 0;
                for ($i = 0; $i < count($moyenneDesMatiere); $i++) {
                    $moyUe2 += $moyenneDesMatiere[$i]['moyMat'] * $moyenneDesMatiere[$i]['coeff'];
                    $coeffUe2 += $moyenneDesMatiere[$i]['coeff'];
                }
                if ($coeffUe2 === 0) {
                    $moyUe2 = 0;
                } else {
                    $moyUe2 /= $coeffUe2;
                }
                ?>
            </section>

            <!-- ANCHOR RESUMER  -->
            <section class="note">

                <!-- ANCHOR Bandeau resume, uniquement PC/Tablette -->
                <div class="row resume-tab around-sm hidden-xs">
                    <div class="col-sm-1 center-sm">
                    </div>
                    <div class="col-sm-2 center-sm btn-etu">
                        <p>Moyenne sur 20</p>
                    </div>
                    <div class="col-sm-2 center-sm btn-etu">
                        <p>Résultats</p>
                    </div>
                </div>
                <!-- Affichage  uniquement sur mobile car pas de bandeau  -->
                <h1 class="hidden-sm hidden-md hidden-lg hidden-xl" id="resultat">Résultats</h1>
                <!-- ANCHOR Resumer UE1 -->
                <!-- Sur pc/tablette on affiche pas les span, car les informations sont contenu dans le bandeau, contrairement au téléphone -->
                <article class="row all-note around-sm sem">
                    <div class="col-sm-1">
                        <h2 class="hidden-sm hidden-md hidden-lg hidden-xl">UE1</h2>
                        <p><span class="hidden-xs">UE1</span></p>
                    </div>
                    <div class="col-sm-2 center-sm btn-green">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20
                                <br></span><?php echo round($moyUe1, 3); ?></p>

                    </div>
                    <?php
                    if ($moyUe1 >= 8) {
                    ?>
                        <div class="col-sm-2 center-sm btn-green mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span> UE Validé</p>
                        </div>
                    <?php
                    } elseif ($moyUe1 < 8) {
                    ?>
                        <div class="col-sm-2 center-sm btn-red mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>UE Échoué</p>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-sm-2 center-sm btn-orange mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>UE Échoué</p>
                        </div>
                    <?php
                    }
                    ?>

                </article>
                <!-- ANCHOR Resumer UE2 -->
                <!-- Sur pc/tablette on affiche pas les span, car les informations sont contenu dans le bandeau, contrairement au téléphone -->
                <article class="row all-note around-sm sem">
                    <div class="col-sm-1">
                        <h2 class="hidden-sm hidden-md hidden-lg hidden-xl">UE2</h2>
                        <p><span class="hidden-xs">UE2</span></p>
                    </div>
                    <div class="col-sm-2 center-sm btn-green">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20
                                <br></span><?php echo round($moyUe2, 3); ?></p>
                    </div>
                    <?php
                    if ($moyUe2 >= 8) {
                    ?>
                        <div class="col-sm-2 center-sm btn-green mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span> UE Validé</p>
                        </div>
                    <?php
                    } elseif ($moyUe2 < 8) {
                    ?>
                        <div class="col-sm-2 center-sm btn-red mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>UE Échoué</p>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-sm-2 center-sm btn-orange mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>UE Échoué</p>
                        </div>
                    <?php
                    }
                    ?>

                </article>
                <!-- ANCHOR Resumer S1 -->
                <!-- Sur pc/tablette on affiche pas les span, car les informations sont contenu dans le bandeau, contrairement au téléphone -->
                <article class="row all-note around-sm sem">
                    <div class="col-sm-1">
                        <h2 class="hidden-sm hidden-md hidden-lg hidden-xl">Semestre <?php echo $semestre; ?></h2>
                        <p><span class="hidden-xs">S<?php echo $semestre; ?></span></p>
                    </div>
                    <div class="col-sm-2 center-sm btn-green">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20
                                <br></span><?php echo $moyenne; ?></p>
                    </div>
                    <?php
                    if ($moyenne >= 10 && $moyUe1 >= 8 && $moyUe2 >= 8) {
                    ?>
                        <div class="col-sm-2 center-sm btn-green mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span> Semestre Validé
                            </p>
                        </div>
                    <?php
                    } elseif ($moyenne >= 10 && ($moyUe1 < 8 || $moyUe2 < 8)) {
                    ?>
                        <div class="col-sm-2 center-sm btn-red mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>Semestre Échoué</p>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col-sm-2 center-sm btn-red mr-14">
                            <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>Semestre Échoué</p>
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
                <p class="as-small">Made with ❤️ by <a href="https://erosya.fr" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr / support@quentium.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
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