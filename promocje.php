<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 10.05.2017
 * Time: 00:49
 */

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['astr'] != "promocje") {
    $_SESSION['astr'] = "promocja";
}
require 'polacz.php';

ob_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Promocje</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css"/>
    <script type="text/javascript" src="skrypt.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div style="display: table; margin: 12px auto; padding: 16px 24px; background-color: #ffffff; width: 97%; height: auto;">
            <?php
            echo '<h1>Aktualne promocje:</h1><br/>';
            $q = mysqli_query($pol, 'SELECT * FROM promocje');
            while ($prq = mysqli_fetch_object($q)) {
                echo '<div class="promocja" title="Naciśnij, aby zobaczyć szczegóły promocji" onclick="location.href=\'promocja.php?idpromo=' . $prq->id . '\';" >';
                echo '<div class="minp" >';
                echo '<img src="panelCMS/pimg/' . $prq->baner . '" height="108" />';
                echo '</div>';
                echo '<div class="etykp"><a style="font-size: 120%;" href="promocja.php?idpromo=' . ($prq->id) . '" >' . ($prq->tytul) . '</a><br/><br/>';
                echo '<span style="font-style: italic; font-weight: 600;">Ilość produktów<br/> w promocji: ' . count(explode(";", $prq->id_prod)) . ' szt</span>';
                echo '</div>';
                echo '</div>';
            }
            ?>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>