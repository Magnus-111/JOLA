<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();
$_SESSION['astr'] = "index";
require 'polacz.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Pomyślna rejestracja</title>
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
        <div style="margin: 14px auto; padding: 16px 25px; background-color: #fff; width: 96%;">
            <br/><br/>
            <h1>Witamy w naszym sklepie</h1>
            <br/>
            <h2>Rejestracja zakończona pomyślnie</h2>
            <h4>Na poniższy adres e-mail zostało wysłane podsumowanie rejestracji. Otwórz wiadomość i aktywuj konto. </h4>
            <?php echo '&nbsp;<a href="https://'.$_SESSION['emailr'].'" >'.$_SESSION['emailr'].'</a>'; ?>
            <br/><br/>
            <h3>Życzymy udanych zakupów w naszym sklepie.</h3>
            <br/>
            <h4>Poleć nas swoim znajomym.</h4>
            &nbsp; <a href="#">facebook</a> <a href="#">google+</a> <a href="#">twitter</a>
            <br/><br/>
            <h3>Jeśli w ciągu 5 dni roboczych nie aktywujesz konta, to zostanie ono usunięte z naszego sklepu.</h3>
            <br/><br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>