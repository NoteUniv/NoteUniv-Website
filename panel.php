<?php
session_start();
require "vendor/autoload.php";
// recupération des variables d'environnement
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
// Récupération Numéro Etudiant du formulaire
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
} else {
    header('Location: https://noteuniv.fr');
}

$sql_all_notes = "SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, name_ens, type_note, note_semester, note_total, median, variance, deviation FROM global ORDER BY note_date ASC";
$list_notes = $bdd->query($sql_all_notes);
$s1Ue1 = [];
$s1Ue2 = [];
$s2Ue1 = [];
$s2Ue2 = [];
while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $matiere = $note['note_code'];
    $semestreUe = $note['note_semester'];
    switch ($semestreUe) {
        case 'S1UE1':
            array_push($s1Ue1, $matiere);
            break;
        case 'S1UE2':
            array_push($s1Ue2, $matiere);
            break;
        case 'S2UE1':
            array_push($s2Ue1, $matiere);
            break;
        case 'S2UE2':
            array_push($s2Ue2, $matiere);
            break;
        default:
            echo "Billy, y'a un pb. Appel le SAV !";
            break;
    }
}
$s1Ue1 = array_unique($s1Ue1, SORT_STRING);
$s1Ue2 = array_unique($s1Ue2, SORT_STRING);
$s2Ue1 = array_unique($s2Ue1, SORT_STRING);
$s2Ue2 = array_unique($s2Ue2, SORT_STRING);
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
                    <button class="btn-etu">MMI</button> <br>
                    <button class="btn-etu">SEMESTRE 1</button>
                    <p class="as-small">Ma moyenne générale est :</p>
                    <button class="btn-moy"><?php echo $moyenne; ?> / 20</button>
                    <?php
                    if ($moyenne >= 10) {
                        echo '<p class="green">Je passe au semestre suivant, YEAH !</p>';
                    } else {
                        echo '<p class="red">Merde, j\'ai foirée, je passe pas :(</p>';
                    }
                    ?>
                    <p class="btn-logout"><a href="last.php">Dernières notes</a></p>
                    <p class="btn-logout"><a href="https://noteuniv.fr/">Se déconnecter</a></p>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <div class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <section class="note">
                <!-- Phrase différentes selon le viewport, afin de gagner de la place  -->
                <!-- <h1 class="hidden-xs hidden-sm">Retrouvez vos notes avec NoteUniv !</h1> -->
                <!-- <h1 class="hidden-md hidden-lg hidden-xl">Mes notes</h1> -->
                <h1>PAS A JOUR NSM LAISSEZ MOI DORMIR</h1>

                <!-- Menu de navigation pour mobile  -->
                <div class="row center-xs hidden-sm hidden-md hidden-lg hidden-xl nav">
                    <div class="col-xs-3">
                        <p><a href="#ue1">UE1</a></p>
                    </div>
                    <div class="col-xs-3">
                        <p><a href="#ue2">UE2</a></p>
                    </div>
                    <div class="col-xs-3">
                        <p><a href="#resultat">Recap</a></p>
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
                                <p>Coef</p>
                            </div>
                            <div class="col-sm">
                                <p>Points</p>
                            </div>
                            <div class="col-sm">
                                <p>Notes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                foreach ($s1Ue1 as $key => $value) {

                    $s1Ue1Sql = $bdd->query("SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, name_ens, type_note, note_semester, note_total, median, variance, deviation, type_epreuve FROM global WHERE note_code = '$value' AND note_semester = 'S1UE1' ORDER BY note_date DESC");
                
                ?>
                <!-- ANCHOR Notes par matière 1 -->
                <article class="row all-note">
                    <div class="col-sm-2 matiere first-xs">
                        <p><span><?php echo $value;?></span></p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <?php
                        $i = 0;
                        $point = [];
                        $n = 0;
                    while ($infoNote = $s1Ue1Sql -> fetch()) {
                        
                        $name = utf8_encode($infoNote['name_devoir']);
                        $pdf = $infoNote['name_pdf'];
                        $noteMoyenne = round($infoNote['moy'], 2);
                        $mini = $infoNote['mini'];
                        $maxi = $infoNote['maxi'];
                        $coeff = $infoNote['note_coeff'];
                        $type = $infoNote['type_note'];
                        $date = $infoNote['note_date'];
                        $ens = $infoNote['name_ens'];
                        $totalNote = $infoNote['note_total'];
                        $median = $infoNote['median'];
                        $variance = round($infoNote['variance'], 2);
                        $deviation = round($infoNote['deviation'], 2);
                        $matiere = $infoNote['note_code'];
                        $typeEpreuve = $infoNote['type_epreuve'];
                        $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
                        $noteEtu = $myNote->fetch();
                        $pts = $noteEtu[0] * $coeff;
                        array_push($point, $pts);
                    ?>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="<?php echo $matiere.$i?>"
                                    class="tippy-note">
                                    <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note
                                            <?php echo $i;?><br></span>
                                        <?php 
                                        if ($noteEtu[0] < $mini) {
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
                            <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE1-->
                            <div class="popup">
                                <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                                <div id="<?php echo $matiere.$i;?>">
                                    <div class="user-note">
                                        <h2 class="note-header">Détails de la note</h2>
                                        <p class="b">Nom du devoir / Module :</p>
                                        <p><?php echo $name;?></p>
                                        <p class="b">Enseignant :</p>
                                        <p><?php echo $ens;?></p>
                                        <p class="b">Date de l'épreuve :</p>
                                        <p><?php echo $date;?></p>
                                        <p class="b">Type de note :</p>
                                        <p><?php echo $type;?></p>
                                        <p class="b">Type d'épreuve : </p>
                                        <p><?php echo $typeEpreuve;?></p>
                                        <h2 class="note-header">Et ma promo alors ?</h2>
                                    </div>

                                    <div class="promo-note">
                                        <div class="row center-xs">
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Moyenne</span> <br><?php echo $moyenne;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Median</span> <br><?php echo $median;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Min</span> <br><?php echo $mini;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Max</span> <br><?php echo $maxi;?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row center-xs separation-note">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Total Notes</span> <br><?php echo $totalNote;?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Variance</span> <br><?php echo $variance;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">deviation</span> <br><?php echo $deviation;?>
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
                            $n++;
                            }
                            while ($i < 4) {
                                ?>
                            <div class="col-sm col-xs-6">
                                <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note
                                        <?php echo $i;?><br></span>
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
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coef:</span>
                                    <?php echo $coeff;?></p>
                            </div>
                            <div class="col-xs-4">
                                <p><span
                                        class="hidden-sm hidden-md hidden-lg hidden-xl">Points:</span><?php echo array_sum($point);?>
                                </p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> <?php echo $n;?>
                                </p>
                            </div>
                        </div>
                    </div>
                    </article>
                <?php
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
                                <p>Coef</p>
                            </div>
                            <div class="col-sm">
                                <p>Points</p>
                            </div>
                            <div class="col-sm">
                                <p>Notes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affichage de l'ue2 uniquement sur mobile car pas de bandeau  -->
                <h2 class="hidden-sm hidden-md hidden-lg hidden-xl" id="ue2">UE 2</h2>

                <!-- ANCHOR Notes par matière 2 -->
                <?php
                foreach ($s1Ue2 as $key => $value) {

                    $s1Ue1Sql = $bdd->query("SELECT name_devoir, name_pdf, note_date, moy, mini, maxi, note_code, note_coeff, name_ens, type_note, note_semester, note_total, median, variance, deviation, type_epreuve FROM global WHERE note_code = '$value' AND note_semester = 'S1UE2' ORDER BY note_date DESC");
                
                ?>
                <!-- ANCHOR Notes par matière 1 -->
                <article class="row all-note">
                    <div class="col-sm-2 matiere first-xs">
                        <p><span><?php echo $value;?></span></p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <?php
                        $i = 0;
                        $point = [];
                        $n = 0;
                    while ($infoNote = $s1Ue1Sql -> fetch()) {
                        
                        $name = utf8_encode($infoNote['name_devoir']);
                        $pdf = $infoNote['name_pdf'];
                        $noteMoyenne = round($infoNote['moy'], 2);
                        $mini = $infoNote['mini'];
                        $maxi = $infoNote['maxi'];
                        $coeff = $infoNote['note_coeff'];
                        $type = $infoNote['type_note'];
                        $date = $infoNote['note_date'];
                        $ens = $infoNote['name_ens'];
                        $totalNote = $infoNote['note_total'];
                        $median = $infoNote['median'];
                        $variance = round($infoNote['variance'], 2);
                        $deviation = round($infoNote['deviation'], 2);
                        $matiere = $infoNote['note_code'];
                        $typeEpreuve = $infoNote['type_epreuve'];
                        $myNote = $bdd->query("SELECT note_etu FROM $infoNote[name_pdf] WHERE id_etu = $id_etu");
                        $noteEtu = $myNote->fetch();
                        $pts = $noteEtu[0] * $coeff;
                        array_push($point, $pts);
                    ?>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="<?php echo $matiere.$i?>"
                                    class="tippy-note">
                                    <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note
                                            <?php echo $i;?><br></span>
                                        <?php 
                                        if ($noteEtu[0] < $mini) {
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
                            <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE1-->
                            <div class="popup">
                                <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                                <div id="<?php echo $matiere.$i;?>">
                                    <div class="user-note">
                                        <h2 class="note-header">Détails de la note</h2>
                                        <p class="b">Nom du devoir / Module :</p>
                                        <p><?php echo $name;?></p>
                                        <p class="b">Enseignant :</p>
                                        <p><?php echo $ens;?></p>
                                        <p class="b">Date de l'épreuve :</p>
                                        <p><?php echo $date;?></p>
                                        <p class="b">Type de note :</p>
                                        <p><?php echo $type;?></p>
                                        <p class="b">Type d'épreuve : </p>
                                        <p><?php echo $typeEpreuve;?></p>
                                        <h2 class="note-header">Et ma promo alors ?</h2>
                                    </div>

                                    <div class="promo-note">
                                        <div class="row center-xs">
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Moyenne</span> <br><?php echo $moyenne;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Median</span> <br><?php echo $median;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Min</span> <br><?php echo $mini;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Max</span> <br><?php echo $maxi;?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row center-xs separation-note">
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Total Notes</span> <br><?php echo $totalNote;?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">Variance</span> <br><?php echo $variance;?></p>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="btn-etu">
                                                    <p> <span class="b">deviation</span> <br><?php echo $deviation;?>
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
                            $n++;
                            }
                            while ($i < 4) {
                                ?>
                            <div class="col-sm col-xs-6">
                                <p> <span class="hidden-sm hidden-md hidden-lg hidden-xl">Note
                                        <?php echo $i;?><br></span>
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
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coef:</span>
                                    <?php echo $coeff;?></p>
                            </div>
                            <div class="col-xs-4">
                                <p><span
                                        class="hidden-sm hidden-md hidden-lg hidden-xl">Points:</span><?php echo array_sum($point);?>
                                </p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> <?php echo $n;?>
                                </p>
                            </div>
                        </div>
                    </div>
                    </article>
                <?php
}
?>
            </section>

            <!-- ANCHOR RESUMER  -->
            <article class="note">

                <!-- ANCHOR Bandeau resume, uniquement PC/Tablette -->
                <div class="row resume-tab around-sm hidden-xs">
                    <div class="col-sm-1 center-sm">
                    </div>
                    <div class="col-sm-2 center-sm">
                        <p>Points à avoir</p>
                    </div>
                    <div class="col-sm-2 center-sm">
                        <p>Points possédés</p>
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

                <!-- ANCHOR Notes par matière 2 -->
                <!-- Sur pc/tablette on affiche pas les span, car les informations sont contenu dans le bandeau, contrairement au téléphone -->
                <div class="row all-note around-sm">
                    <div class="col-sm-1">
                        <h2 class="hidden-sm hidden-md hidden-lg hidden-xl">UE1</h2>
                        <p><span class="hidden-xs">UE1</span></p>
                    </div>
                    <div class="col-sm-2 center-sm">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Total de points à avoir :</span> 260
                        </p>
                    </div>
                    <div class="col-sm-2 center-sm">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Points possédés :</span> 170</p>
                    </div>
                    <div class="col-sm-2 center-sm btn-moy">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Moyenne sur 20 <br></span>13,08</p>
                    </div>
                    <div class="col-sm-2 center-sm btn-moy mr-14">
                        <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Résultats <br></span>UE Validé</p>
                    </div>
                </div>
            </article>
        </div>
    </div>

    </div>
    <!-- SCRIPT EXT -->
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <!-- SCRIPT PERSO -->
    <script src="assets/js/app.js"></script>
</body>

</html>