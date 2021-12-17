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
if ((!empty($_POST["numEtu"]) && is_numeric($_POST["numEtu"]))) {
    $id_etu = htmlspecialchars($_POST["numEtu"]);
    $_SESSION['id_etu'] = $id_etu;
} else if (!empty($_SESSION['id_etu']) && is_numeric($_SESSION['id_etu'])) {
    $id_etu = $_SESSION['id_etu'];
} else {
    header('Location: ./');
}

$sqlEtu = $bdd->query("SELECT tp, promo FROM data_etu WHERE id_etu = $id_etu");
$data = $sqlEtu->fetch();
$tp = $data[0];
$promo = $data[1];

if ($promo === 'LP_DWEB') {
    $promo = 'MMI_DWEB'; // Calendar name
} else {
    $promo = 'MMI_GRAPH_RAJ'; // Use global GRAPH-RAJ calendar name
}

$json_edt_url = file_get_contents("assets/js/edt_url.json");
$edt_url = json_decode($json_edt_url, true);
$linkIcal = $edt_url[$promo]['TP' . $tp];

use ICal\ICal;

try {
    $ical = new ICal('ADECal.ics', array(
        'defaultSpan'                 => 2,
        'defaultTimeZone'             => 'UTC',
        'defaultWeekStart'            => 'MO',
        'disableCharacterReplacement' => false,
        'filterDaysAfter'             => null,
        'filterDaysBefore'            => null,
        'skipRecurrence'              => false,
    ));
    $ical->initUrl($linkIcal, $username = null, $password = null, $userAgent = null);
} catch (\Exception $e) {
    die($e);
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
    <title>NoteUniv | EDT</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flexboxgrid2" type="text/css">
    <!-- CSS EDT  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.min.css">
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
                    <a href="last.php"><span class="btn btn-logout">Dernières notes</span></a>
                    <a href="panel.php"><span class="btn btn-logout">Récapitulatif</span></a>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <h1 class="hidden-xs hidden-sm">L'emploi du temps (TP<?= $tp ?>)</h1>
                <h1 class="hidden-md hidden-lg hidden-xl">EDT (TP<?= $tp ?>)</h1>
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
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        function getColor(title) {
                            if (title.indexOf('TP') !== -1) {
                                return '#F1FA89'; // Yellow
                            } else if (title.indexOf('TD') !== -1) {
                                return '#B5EAEA'; // Blue
                            } else if (title.indexOf('CI') !== -1) {
                                return '#A6DE93'; // Green
                            } else if (title.indexOf('CM') !== -1 || title.indexOf('Confé') !== -1) {
                                return '#F6AE99'; // Orange
                            } else if (title.indexOf('PT') !== -1) {
                                return '#F38BA0'; // Red
                            } else if (title.indexOf('soutenance') !== -1) {
                                return '#FF7171'; // Ultrared
                            } else {
                                return '#6F4C5B'; // Brown
                            }
                        }

                        let tippyNote;

                        let calendarEl = document.getElementById('calendar');

                        let calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'timeGridWeek',
                            height: 'auto',
                            headerToolbar: {
                                left: 'prev,next,today',
                                center: 'title',
                                right: 'timeGridDay,timeGridWeek,dayGridMonth'
                            },
                            locale: 'fr',
                            buttonText: {
                                today: 'Aujourd\'hui',
                                month: 'Mois',
                                week: 'Semaine',
                                day: 'Jour'
                            },
                            allDaySlot: false,
                            slotMinTime: '08:30',
                            slotMaxTime: '18:30',
                            nowIndicator: true,
                            slotLabelInterval: '00:30',
                            weekends: false,
                            events: [
                                <?php
                                $events = $ical->sortEventsWithOrder($ical->events());
                                foreach ($events as $event) {
                                    $dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);
                                    $start = $dtstart->format('c');
                                    $dtend = $ical->iCalDateToDateTime($event->dtend_array[3]);
                                    $end = $dtend->format('c');
                                    $dtmodified = $ical->iCalDateToDateTime($event->last_modified);
                                    $modified = $dtmodified->format('c');
                                    $title = addslashes(str_replace('_', ' ',  $event->summary));
                                    $location = addslashes(str_replace(['salle non définie', ',', '_'], ['', '', ' '], $event->location));
                                    $description = explode("\n", $event->description);
                                    $group = addslashes(str_replace('_', ' ', $description[0]));
                                    $teacher = addslashes($description[1]);
                                ?> {
                                        subject: '<?= $title ?>',
                                        location: '<?= $location ?>',
                                        teacher: '<?= $teacher ?>',
                                        group: '<?= $group ?>',
                                        start: '<?= $start ?>',
                                        end: '<?= $end ?>',
                                        modified: '<?= $modified ?>',
                                        textColor: 'black',
                                        backgroundColor: getColor('<?= $title ?>'),
                                    },
                                <?php
                                }
                                ?>
                            ],
                            eventDidMount: function(info) {
                                var elTitle = info.el.querySelector('.fc-event-title');
                                elTitle.innerHTML = '<span style="font-size: 16px;">' + info.event.extendedProps.subject + '</span>';
                                elTitle.innerHTML += '<br>' + info.event.extendedProps.location + '<br>';
                                elTitle.innerHTML += '<i>' + info.event.extendedProps.teacher + '</i>';
                            },
                            eventClick: function(info) {
                                if (tippyNote) {
                                    tippyNote.destroy();
                                }

                                tippyNote = tippy(info.el, {
                                    content: '<div class="tippy-note">' +
                                        '<h3>' + info.event.extendedProps.subject + '</h3>' +
                                        '<p>Salle : ' + info.event.extendedProps.location + '</p>' +
                                        '<p>Prof : ' + info.event.extendedProps.teacher + '</p>' +
                                        '<p>Groupe : ' + info.event.extendedProps.group + '</p>' +
                                        '<p>Début : ' + info.event.start.toLocaleString() + '</p>' +
                                        '<p>Fin : ' + info.event.end.toLocaleString() + '</p>' +
                                        '<p>Modifié : ' + new Date(info.event.extendedProps.modified).toLocaleString() + '</p>' +
                                        '</div>',
                                    allowHTML: true,
                                    arrow: true,
                                    placement: 'top',
                                    trigger: 'click',
                                    interactive: true,
                                    theme: 'edt',
                                    duration: [300, 0],
                                    appendTo: document.querySelector('body'),
                                    onHidden: function() {
                                        tippyNote.destroy();
                                    }
                                });
                                tippyNote.show();
                            }
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
                <p class="as-small">Made with ❤️ by <a href="https://oserya.fr/" target="_BLANK">Oserya</a> | <span class="tippy-note" data-tippy-content="QuentiumYT#0207 | Ynohtna#0001 / pro@quentium.fr | contact@anthony-adam.fr">Nous contacter</span> | <a href="terms.html">Mentions légales</a></p>
            </div>
        </div>
        <!-- SCRIPT EXT -->
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid"></script>
        <script src="https://unpkg.com/@popperjs/core"></script>
        <script src="https://unpkg.com/tippy.js"></script>
        <!-- SCRIPT PERSO -->
        <script src="assets/js/app.js"></script>
    </footer>
</body>

</html>