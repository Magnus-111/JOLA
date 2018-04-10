<!DOCTYPE html>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['astr'] = "index";

ob_start();

require 'polacz.php';
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Koszty dostawy</title>
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
        <div style="margin: 12px auto; padding: 20px 24px; background-color: #fff; text-align: center;">
            <h2>Koszty i sposoby dostawy zamówień:</h2>
            <br/>
            <table class="tabela" border="0" cellspacing="4" cellpadding="8">
                <thead>
                <tr>
                    <th>Firma dostarczająca</th>
                    <th>Przedział wagowy zamówienia</th>
                    <th>Cena</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $d = mysqli_query($pol, 'SELECT * FROM wysylka');
                while ($od = mysqli_fetch_object($d)) {
                    echo '<tr>';
                    echo '<td>' . $od->typ . '</td>';
                    if ($od->wagamax > 999) {
                        echo '<td>Brak ograniczenia</td>';
                    } else {
                        echo '<td>' . $od->wagamin . ' kg - ' . $od->wagamax . ' kg</td>';
                    }
                    echo '<td>' . number_format($od->cena, 2, ',', ' ') . ' zł</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <br/>
            <h2>Koszty dodatkowe na życzenie:</h2>
            <br/>
            <table class="tabela" border="0" cellspacing="4" cellpadding="8">
                <thead>
                <tr>
                    <th>Rodzaj usługi</th>
                    <th>Cena</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $dd = mysqli_query($pol, 'SELECT * FROM dodatkowe ORDER BY `dodatkowe`.`id` ASC');
                while ($odd = mysqli_fetch_object($dd)) {
                    echo '<tr>';
                    echo '<td onclick="location.href=\'produkt.php?iddod='.$odd->id.'\';" style="cursor:pointer;">' . $odd->nazwa . '</td>';
                    echo '<td> +' . number_format($odd->cena, 2, ',', ' ') . ' zł</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>