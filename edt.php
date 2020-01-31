<?php
session_start();
// Dépendances
require_once "vendor/autoload.php";

// Changement de semestre
if (!isset($_COOKIE['semestre']) || !is_numeric($_COOKIE['semestre'])) {
    setcookie("semestre", "1", strtotime('+360 days'));
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
    header('Location: https://noteuniv.fr');
}

$sqlEtu = $bdd->query("SELECT tp, promo FROM data_etu WHERE id_etu = $id_etu");
$data = $sqlEtu->fetch();
$tp = $data[0];
$promo = $data[1];

$json_edt_url = file_get_contents("assets/js/edt_url.json");
$edt_url = json_decode($json_edt_url, true);
$linkIcal = $edt_url[$promo]['TP' . $tp];

use ICal\ICal;

try {
    $ical = new ICal('ICal.ics', array(
        'defaultSpan'                 => 2,     // Default value
        'defaultTimeZone'             => 'UTC',
        'defaultWeekStart'            => 'MO',  // Default value
        'disableCharacterReplacement' => false, // Default value
        'filterDaysAfter'             => null,  // Default value
        'filterDaysBefore'            => null,  // Default value
        'skipRecurrence'              => false, // Default value
    ));
    $ical->initUrl($linkIcal, $username = null, $password = null, $userAgent = null);
} catch (\Exception $e) {
    die($e);
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
    <meta name="theme-color" content="#110133">
    <title>NoteUniv - EDT</title>
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
    <!-- CSS EXT-->
    <link rel="stylesheet" href="assets/css/flexboxgrid2.css" type="text/css">
    <!-- CSS PERSO-->
    <link rel="stylesheet" href="assets/css/stylePanel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/edt.css" type="text/css">
    <!-- CSS EDT  -->
    <link href='assets/packages/core/main.css' rel='stylesheet' />
    <link href='assets/packages/daygrid/main.css' rel='stylesheet' />
    <link rel="stylesheet" href="assets/packages/timegrid/main.css">
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
                    <span class="btn btn-moy">
                        <span class="tippy-note" data-tippy-content="<a href='ranking.php'>Besoin de voir ta grandeur ?</a>"><?= $moyenne ?> / 20</span>
                    </span>
                    <?php
                    if ($moyenne >= 15) {
                        echo '<p class="green">Un Dieu !</p>';
                    } else if ($moyenne >= 13) {
                        echo '<p class="green">Honnêtement ? OKLM gros !</p>';
                    } elseif ($moyenne >= 10) {
                        echo '<p class="orange">ALLEEEZZZ ! ça passe !</p>';
                    } else {
                        echo '<p class="red">Aïe, trql on se motive !</p>';
                    }
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
                <h1 class="hidden-xs hidden-sm">L'emploi du temps (TP<?php echo $tp; ?>)</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">EDT (TP<?php echo $tp; ?>)</h1>
                <form action="assets/include/edt_post.php" method="POST">
                    <select name="tp" class="custom-select" onchange="this.form.submit()">
                        <?php
                        foreach ($edt_url[$promo] as $key => $value) {
                            if ($tp === $key[-1]) {
                                echo '<option selected="selected" value="' . $key[-1] . '">' . $key . '</option>';
                            } else {
                                echo '<option value="' . $key[-1] . '">' . $key . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <!-- <input type="submit" value="Changer de TP !" class="btn-sub"> -->
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var calendarEl = document.getElementById('calendar');

                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            plugins: ['dayGrid', 'timeGrid'], // an array of strings!
                            defaultView: 'timeGridWeek',
                            height: 'auto',
                            footer: {
                                center: 'timeGridWeek,dayGridMonth',
                            },
                            locale: 'fr',
                            buttonText: {
                                today: 'Aujourd\'hui',
                                month: 'Mois',
                                week: 'Semaine',
                                day: 'Jour'
                            },
                            allDaySlot: false,
                            minTime: "08:30:00",
                            maxTime: "18:30:00",
                            nowIndicator: true,
                            slotLabelInterval: "00:30",
                            weekends: false,
                            events: [
                                <?php
                                $events = $ical->sortEventsWithOrder($ical->events());
                                foreach ($events as $event) {
                                    $title = $event->summary;
                                    $dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);
                                    $start = $dtstart->format('c');
                                    $dtend = $ical->iCalDateToDateTime($event->dtend_array[3]);
                                    $end = $dtend->format('c');
                                    $location = $event->location;
                                    $descri = $event->description;
                                    $title = str_replace('_', ' ', $title);
                                    $location = str_replace('_', ' ', $location);
                                    if (preg_match('/^WEB?/', $title)) {
                                        $class = 'web';
                                    } else if (preg_match('/^ANG/', $title)) {
                                        $class = 'ang';
                                    } else if (preg_match('/^BD/', $title)) {
                                        $class = 'bd';
                                    } else if (preg_match('/^ART/', $title)) {
                                        $class = 'art';
                                    } else if (preg_match('/^COM/', $title)) {
                                        $class = 'com';
                                    } else if (preg_match('/^ECO/', $title)) {
                                        $class = 'eco';
                                    } else if (preg_match('/^IC/', $title)) {
                                        $class = 'ic';
                                    } else if (preg_match('/^ALL/', $title) || preg_match('/^ESP/', $title)) {
                                        $class = 'lv';
                                    } else if (preg_match('/^MEDIA/', $title)) {
                                        $class = 'media';
                                    } else if (preg_match('/^PRJ/', $title)) {
                                        $class = 'prj';
                                    } else if (preg_match('/^AV/', $title)) {
                                        $class = 'av';
                                    } else if (preg_match('/^CREA/', $title)) {
                                        $class = 'crea';
                                    } else if (preg_match('/^INFO/', $title)) {
                                        $class = 'info';
                                    } else if (preg_match('/^REZS/', $title)) {
                                        $class = 'rezs';
                                    } else if (preg_match('/^SCI/', $title)) {
                                        $class = 'sci';
                                    } else if (preg_match('/^PTWEB/', $title)) {
                                        $class = 'ptweb';
                                    } else {
                                        $class = 'none';
                                    }
                                ?> {

                                        title: '<?php echo $title . '\n' . $location; ?>',
                                        start: '<?php echo $start; ?>',
                                        end: '<?php echo $end; ?>',
                                        textColor: 'black',
                                        classNames: '<?php echo $class; ?>',
                                    },
                                <?php
                                }
                                ?>
                            ],
                        });

                        calendar.render();
                    });
                </script>
                <div id="calendar"></div>
            </section>
        </div>
    </div>
    <footer>
        <div class="row center-xs">
            <div class="col-xs-12">
                <p class="as-small">Made with ❤️ By <a href="https://erosya.fr/" target="_BLANK">Erosya</a> | <span class="tippy-note" data-tippy-content="Discord: Ynohtna#0001 / QuentiumYT#0207 | contact@anthony-adam.fr / support@quentium.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
            </div>
        </div>
        <!-- SCRIPT EXT -->
        <script src='assets/packages/core/main.js'></script>
        <script src='assets/packages/daygrid/main.js'></script>
        <script src='assets/packages/timegrid/main.js'></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/tippy-bundle.iife.min.js"></script>
        <!-- SCRIPT PERSO -->
        <script src="assets/js/app.js"></script>
    </footer>
</body>

</html>