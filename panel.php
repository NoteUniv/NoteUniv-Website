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
    
//Récupération Numéro Etudiant du formulaire
if (!empty($_SESSION["id_etu"]) && is_numeric($_SESSION["id_etu"])) {
    $id_etu = htmlspecialchars($_SESSION['id_etu']);
} else {
    header('Location: https://noteuniv.fr');
}
$sql_all_notes = "SELECT name_pdf FROM global";
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes de l'élève
while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    $my_note = $bdd->query($sql_note);
    $noteEtudiant = $my_note->fetch();
    array_push($totalNote, $noteEtudiant[0]); // push de ces notes dans le tableau pour moyenne
}
$moyenne = array_sum($totalNote) / count($totalNote); // on fait la moyenne : Ensemble des notes du tableau / nbr de note
$moyenne = round($moyenne, 2)
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
                    <p class="btn-logout"><a href="https://noteuniv.fr">Se déconnecter</a></p>
                </div>
            </div>
        </aside>
        <!-- ANCHOR LEFT SIDE -->
        <section class="col-lg-9 col-sm-12">
            <!-- ANCHOR NOTES -->
            <article class="note">
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

$sql_all_notes = "SELECT id, name_devoir, name_pdf, note_date, moy, mini, maxi FROM global ORDER BY note_date DESC";
$list_notes = $bdd->query($sql_all_notes);
while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    $name = utf8_encode($note['name_devoir']);
    $pdf = $note['name_pdf'];
    $date = $note['note_date'];
    $noteMoyenne = round($note['moy'], 2);
    $mini = $note['mini'];
    $maxi = $note['maxi'];
    $sqlNote = "SELECT note_etu FROM $note[name_pdf] WHERE id_etu = $id_etu";
    $myNote = $bdd->query($sqlNote);
    $noteEtu = $myNote->fetch();
    $tab = explode("_", $pdf);
    $arrayToReplace = ['1', '2', '3', '4', '5', '-', '_'];

    if (count($tab) > 8) {
        if (ctype_upper(str_replace($arrayToReplace, '', $tab[5]))) {
            $matiere = $tab[5];
        } elseif (ctype_upper(str_replace($arrayToReplace, '', $tab[6]))) {
            $matiere = $tab[6];
        }
    } else {
        if (ctype_upper(str_replace($arrayToReplace, '', $tab[4]))) {
            $matiere = $tab[4];
        } elseif (ctype_upper(str_replace($arrayToReplace, '', $tab[5]))) {
            $matiere = $tab[5];
        } elseif (ctype_upper(str_replace($arrayToReplace, '', $tab[6]))) {
            $matiere = $tab[6];
        }
    }

}
    ?>
                <!-- ANCHOR Notes par matière 1 -->
                <div class="row all-note">
                    <div class="col-sm-2 matiere first-xs">
                        <p><span>Anglais</span></p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="ang1" class="tippy-note"><p> <span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 1<br></span>
                                        12 </p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="ang2" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 2<br></span>
                                        14</p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="ang3" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 3<br></span>
                                        /</p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="ang4" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 4<br></span>
                                        /</p></a>
                            </div>
                        </div>
                    </div>

                    <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE1-->
                    <div class="popup">
                        <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                        <div id="ang1">
                            <div class="user-note">
                                <h2 class="note-header">Détails de la note</h2>
                            <p class="b">Nom du devoir / Module :</p>
                            <p>Tp évalué "Mise en réseau et partage de documents"</p>
                            <p class="b">Enseignant :</p>
                            <p>Loux dominique / Dieb Eric</p>
                            <p class="b">Date de l'épreuve :</p>
                            <p>Non renseignée</p>
                            <p class="b">Type de note :</p>
                            <p>note unique</p>
                            <p class="b">Type d'épreuve : </p>
                            <p>Autre</p>
                            <h2 class="note-header">Et ma promo alors ?</h2>
                            </div>
                            
                            <div class="promo-note">
                                <div class="row center-xs">
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row center-xs separation-note">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="ang2">
                            <p>test</p>
                        </div>
                        <div id="ang3">
                            <p>test</p>
                        </div>
                        <div id="ang4">
                            <p>test</p>
                        </div>
                    </div>
                    <!-- Fin intégration note modales  -->

                    <div class="col-sm-4">
                        <div class="row center-xs">
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coef:</span> 2</p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Points:</span> 52</p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> 2</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="row all-note">
                    <div class="col-sm-2 matiere first-xs">
                        <p><span>Audiovisuel</span></p>
                    </div>
                    <!-- Si mobile, on affiche les notes à la fin, et les coef en 2ème  -->
                    <div class="col-sm-6 last-xs initial-order-sm">
                        <div class="row center-sm note-par-matiere">
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="av1" class="tippy-note"><p> <span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 1<br></span>
                                        12 </p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="av2" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 2<br></span>
                                        14</p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="av3" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 3<br></span>
                                        /</p></a>
                            </div>
                            <div class="col-sm col-xs-6">
                                <a href="javascript:void(0);" data-template="av4" class="tippy-note"><p><span
                                            class="hidden-sm hidden-md hidden-lg hidden-xl">Note 4<br></span>
                                        /</p></a>
                            </div>
                        </div>
                    </div>

                    <!-- ANCHOR INTEGRATION DES NOTES DANS MODALES UE2-->
                    <div class="popup">
                        <!-- Les Id des div sont lié au data-template du <a> des notes. Au clic, le contenu de la div est mis en popup  -->
                        <div id="av1">
                            <div class="user-note">
                                <h2 class="note-header">Détails de la note</h2>
                            <p class="b">Nom du devoir / Module :</p>
                            <p>Tp évalué "Mise en réseau et partage de documents"</p>
                            <p class="b">Enseignant :</p>
                            <p>Loux dominique / Dieb Eric</p>
                            <p class="b">Date de l'épreuve :</p>
                            <p>Non renseignée</p>
                            <p class="b">Type de note :</p>
                            <p>note unique</p>
                            <p class="b">Type d'épreuve : </p>
                            <p>Autre</p>
                            <h2 class="note-header">Et ma promo alors ?</h2>
                            </div>
                            
                            <div class="promo-note">
                                <div class="row center-xs">
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row center-xs separation-note">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <div class="btn-etu">
                                            <p> <span class="b">Note Max</span> <br>5</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div id="av2">
                            <p>test</p>
                        </div>
                        <div id="av3">
                            <p>test</p>
                        </div>
                        <div id="av4">
                            <p>test</p>
                        </div>
                    </div>
                    <!-- Fin intégration note modales  -->


                    <div class="col-sm-4">
                        <div class="row center-xs">
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Coef:</span> 2</p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">points:</span> 52</p>
                            </div>
                            <div class="col-xs-4">
                                <p><span class="hidden-sm hidden-md hidden-lg hidden-xl">Notes:</span> 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

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
        </section>
    </div>

    </div>
        <!-- SCRIPT EXT -->
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    <!-- SCRIPT PERSO -->
    <script src="assets/js/app.js"></script>
    <!-- BLOC NOTE   -->
    <?php

    // $sql_all_notes = "SELECT name_pdf FROM global";
    // $list_notes = $bdd->query($sql_all_notes);
    // $totalNote = []; // tableau de toutes les notes de l'élève
    // while ($note = $list_notes->fetch()) { // note = matière + date (nom du PDF)
    //     $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    //     $my_note = $bdd->query($sql_note);
    //     $noteEtudiant = $my_note->fetch();

    //     echo $note[0] . " -> " . $noteEtudiant[0] . "<br>";
    //     array_push($totalNote, $noteEtudiant[0]); // push de ces notes dans le tableau pour moyenne
    // }
    // $moyenne = array_sum($totalNote) / count($totalNote); // on fait la moyenne : Ensemble des notes du tableau / nbr de note
    // echo "<br> <p> Votre Moyenne est de : <strong> " . $moyenne . "</strong>";
    ?>
</body>

</html>
=======
include("bdd.php");

$id_etu = $_POST["numEtu"];

$sql_all_notes = "SELECT name_pdf FROM global";
$list_notes = $bdd->query($sql_all_notes);
$totalNote = []; // tableau de toutes les notes
while ($note = $list_notes->fetch()) {
    $sql_note = "SELECT note_etu FROM $note[0] WHERE id_etu = $id_etu";
    $my_note = $bdd->query($sql_note);
    $mynote = $my_note->fetch();
    echo $note[0] . " -> " . $mynote[0] . "<br>";
    array_push($totalNote, $mynote[0]);
}
$moyenne = array_sum($totalNote) / count($totalNote);
echo "<br>";
echo "<p> Votre Moyenne est de : <strong> " . $moyenne . "</strong></p>";

