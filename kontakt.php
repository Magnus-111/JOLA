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
    <title>JOLA - Butik internetowy - Kontakt</title>
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
            <div style="display: table; position: relative; top: 0; right: 0; margin: 2px auto; width: 100%; height: 33%;">
                <iframe frameborder="0" style="border:0; width: 100%; min-height: 450px; height: 50%;" allowfullscreen src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d611.1905084054996!2d21.02060282926669!3d52.211371820497064!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471eccde67c5a7c5%3A0x429a96f791af49bd!2sPu%C5%82awska+11%2C+Warszawa!5e0!3m2!1spl!2spl!4v1488652216743">
                </iframe>
            </div>
            <h2>Kontakt: </h2><br/>
            <h1 style="margin: 2px; font-style: italic;"> JOLA </h1>
            <h3>Puławska 11 (obok Grycana)<br/>02-515 Warszawa</h3><br/>
            <h4>Telefon: <a href="tel:+48000000000">+48 000 000 000</a></h4>
            <h4>E-mail: <a href="mailto:kontakt@d.pl">kontakt@d.pl</a></h4>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>