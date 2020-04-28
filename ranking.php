<?php
session_start();
// Dépendances
require "vendor/autoload.php";

// Changement de semestre
if (!isset($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    setcookie("semestre", "2", strtotime('+360 days'));
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
    <meta name="title" content="Noteuniv, IUT Haguenau">
    <meta name="description" content="Retrouvez plus facilement vos notes de l'IUT de Haguenau grâce à NoteUniv !">
    <meta name="keywords" content="noteuniv, haguenau, note iut haguenau, emploi du temps mmi, note mmi, noteuniv mmi">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="15 days">
    <meta name="author" content="Ynohtna, Quentium">
    <title>NoteUniv | Ranking</title>
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
                    <a href="last.php"><span class="btn btn-logout">Dernières notes</span></a>
                    <a href="panel.php"><span class="btn btn-logout">Récapitulatif</span></a>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">El Classement de la muerté</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">Classement</h1>
                <div class="row">
                    <div class="col-xs-6">
                        <p><a href="#me">Cliquez moi pour allez à votre position !</a></p>
                    </div>
                    <div class="col-xs-6">
                        <?php
                        $sqlEtu = $bdd->query('SELECT ranking FROM data_etu WHERE id_etu = ' . $id_etu);
                        $ranking = $sqlEtu->fetch();
                        $ranking = $ranking[0];
                        if ($ranking == 1) {
                        ?>
                            <form action="assets/include/ranking_post.php" method='POST' class="ranking-form">
                                <label for="rank">Cliquez ici pour vous cacher du classement et et garder votre puissance secrète !</label>
                                <br>
                                <input type="checkbox" name="rank" id="rank" value="hide"><input type="submit" value="Je valide" class="btn-sub">
                            </form>
                        <?php
                        } else {
                        ?>
                            <form action="assets/include/ranking_post.php" method='POST' class="ranking-form">
                                <label for="rank">Cliquez ici pour vous afficher dans le classement et afficher votre puissance au monde !</label>
                                <br>
                                <input type="checkbox" name="rank" id="rank" value="show"><input type="submit" value="Je valide" class="btn-sub">
                            </form>
                        <?php
                        }
                        ?>
                    </div>
                </div>

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
                                <p>Étudiant</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 center-sm">
                        <p>Récompense</p>
                    </div>
                </div>

                <!-- ANCHOR Notes -->
                <?php
                $sqlRank = "SELECT id_etu, moy_etu FROM ranking_s$semestre ORDER BY moy_etu DESC";
                $sqlMoy = $bdd->query($sqlRank);
                $i = 1;
                while ($moy = $sqlMoy->fetch()) {
                    $sqlEtu = $bdd->query('SELECT ranking FROM data_etu WHERE id_etu = ' . $moy[0]);
                    $is_ranking = $sqlEtu->fetch();
                    $ranking = $is_ranking[0];
                    if ($ranking == 1) { // ok pour classement
                        echo '<article class="row all-note">';
                    } else {
                        echo '<article class="row all-note hidden-xs hidden-sm hidden-md hidden-lg hidden-xl">';
                    }
                ?>

                    <div class="col-sm-2 matiere first-xs">
                        <p class='titre-mobile'>
                            <?php
                            if ($i < 4) {
                                if ($i == 1) {
                                    echo '<span class="green tippy-note" data-tippy-content="Mieux que les TOP1 Fortnite non ?">' . $i . '</span>';
                                } else {
                                    echo '<span class="green">' . $i . '</span>';
                                }
                            } elseif ($moy[0] == $id_etu) {
                                echo '<span class="green">' . $i . '</span>';
                            } else {
                                echo $i;
                            }
                            ?>
                        </p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                    <?php
                                    if ($ranking == 1) { // ok pour classement
                                        if ($moy[0] == $id_etu) {
                                            echo '<span id="me" class="green">' . $moy[1] . '</span>';
                                        } else {
                                            echo $moy[1];
                                        }
                                    } else {
                                        echo 'Bien tenté !';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-sm col-xs">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Étudiant<br><br></span>
                                    <?php
                                    if ($ranking == 1) { // ok pour classement
                                        echo $moy[0];
                                    } else {
                                        echo 'Bien tenté !';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                    switch ($i) {
                        case '1':
                            print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>La grosse tête</p></div>');
                            break;
                        case '2':
                            print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>Le respect</p></div>');
                            break;
                        case '3':
                            print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span>L\'envie de faire mieux</p></div>');
                            break;
                        case '4':
                            print('<div class="col-sm-4 center-sm last-xs"><p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Récompense : </span><span class="tippy-note" data-tippy-content="CHEH">LE SEUM</span></p></div>');
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
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ by <a href="https://erosya.fr/" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr / support@quentium.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
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