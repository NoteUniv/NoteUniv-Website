<?php
session_start();
// Dépendances
require "vendor/autoload.php";

// Changement de semestre
if (!isset($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    header('Location: https://noteuniv.fr/');
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
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
} else {
    header('Location: https://noteuniv.fr');
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
    <meta name="description" content="Retrouvez facilement vos note de l'iut de haguenau grâce à Noteuniv !">
    <meta name="keywords" content="noteuniv, haguenau, note iut haguenau, emploi du temps mmi, note mmi, noteuniv mmi">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="French">
    <meta name="revisit-after" content="15 days">
    <meta name="author" content="Ynohtna, Quentium">
    <meta name="theme-color" content="#110133">
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
                    <button class="btn-etu"> <span class="tippy-note" data-tippy-content="Changement de Semestre"><a href='?change=true'>SEMESTRE <?php echo $semestre; ?></a></span></button>
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
                    <p class="btn-logout"><a href="last.php">Dernières notes</a></p>
                    <p class="btn-logout"><a href="./">Se déconnecter</a></p>
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
                <p><a href="#me">Cliquez moi pour allez à votre position !</a></p>

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
                switch ($semestre) {
                    case '1':
                        $sqlRank = "SELECT id_etu, moy_etu FROM ranking_s1 ORDER BY moy_etu DESC";
                        break;
                    case '2':
                        $sqlRank = "SELECT id_etu, moy_etu FROM ranking_s2 ORDER BY moy_etu DESC";
                        break;
                    case '3':
                        $sqlRank = "SELECT id_etu, moy_etu FROM ranking_s3 ORDER BY moy_etu DESC";
                        break;
                    case '4':
                        $sqlRank = "SELECT id_etu, moy_etu FROM ranking_s4 ORDER BY moy_etu DESC";
                        break;
                    default:
                        break;
                }
                $sqlMoy = $bdd->query($sqlRank);
                $i = 1;
                while ($moy = $sqlMoy->fetch()) {
                ?>
                    <article class="row all-note">
                        <div class="col-sm-2 matiere first-xs">
                            <p class='titre-mobile'>
                                <?php
                                if ($i < 4) {
                                    if ($i == 1) {
                                        echo '<span class="green tippy-note" data-tippy-content="Mieux que les TOP1 Fortnite non ?">' . $i . '</span>';
                                    } else {
                                        echo '<span class="green">' . $i . '</span>';
                                    }
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
                                    <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne<br><br></span>
                                        <?php
                                        if ($moy[1] == $moyenne && $moy[0] == $id_etu) {
                                            echo '<span id="me" class="green tippy-note-me" data-tippy-content="C\'est toi gros ! J\'espère que ça te va :)">' . $moy[1] . '</span>';
                                        } else {
                                            echo $moy[1];
                                        }
                                        ?> </p>
                                </div>
                                <div class="col-sm col-xs">
                                    <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Étudiant<br><br></span>
                                        <?php
                                        echo $moy[0];
                                        // echo "Supprimé temporairement";
                                        ?>
                                    </p>
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
                <p class="as-small">Made with ❤️ By <a href="https://erosya.fr/" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr / support@quentium.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a> </p>
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