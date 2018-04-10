<?php
if (!isset($_SESSION)) {
    session_start();
}

$_SESSION['astr'] = "index";
require 'polacz.php';

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Pomoc</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css" />
    <script type="text/javascript" src="skrypt.js" ></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff; width: 97%;">
            <img alt="hs" style="display: block; position: relative; margin: 4px auto;" src="ikon/headset.png" />
            <h3 style="display: table; position: relative; margin: 0 auto;">Jakis problem? Chetnie pomożemy :) </h3>
            <h4 style="display: table; position: relative; margin: 0 auto;">Telefon: <a href="tel:+48000000000">+48 000 000 000</a></h4>
            <h4 style="display: table; position: relative; margin: 0 auto;">E-mail: <a href="mailto:kontakt@d.pl">kontakt@d.pl</a></h4>
        </div>
        <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff; width: 97%;">
            <div class="tab" >
                <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'cp');" id="lcp">Częste pytania</a>
                <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'pt');" id="lcp">Przerwy techniczne</a>
            </div>
            <div id="cp" class="tabcontent">
                <div id="p1" class="option-heading" onclick="ps(this, children);">
                    Czemu nie mogę się zalogować? <div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div>
                </div>
                <div id="o1" class="option-content">
                    Odpowiedź I
                </div>
                <div id="p2" class="option-heading" onclick="ps(this, children);">
                    Błędne zamówienie, co zrobić w takim przypadku? <div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div>
                </div>
                <div id="o2" class="option-content">
                    Odpowiedź II
                </div>
                <div id="p3" class="option-heading" onclick="ps(this, children);">
                    Pytanie III <div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div>
                </div>
                <div id="o3" class="option-content">
                    Odpowiedź III
                </div>
            </div>
            <div id="pt" class="tabcontent">
                <h2>Brak zaplanowanych przerw technicznych</h2>
            </div>
            <script type="text/javascript"> document.getElementById("lcp").click();</script>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>