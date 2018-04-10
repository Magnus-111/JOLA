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
    <title>.: JOLA - Butik internetowy - Regulamin :.</title>
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
        <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff; width: 97%; text-align: center;">
            <h1>REGULAMIN</h1>
            <br/>
            <h2>Rodział I</h2>
            <br/>
            <h2>Rodział II</h2>
            <br/>
            <h2>Rodział III</h2>
            <br/>
            <h2>Rodział IV</h2>
            <br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>