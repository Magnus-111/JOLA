<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();

$_SESSION['astr'] = "platnosc";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Opłacanie zamówienia</title>
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
        <div style="display: block; margin: 12px auto; padding: 10px 24px; background-color: #fff;">
            <div class="error">
                <?php
                if (isset($_SESSION['email']) == NULL) {
                    echo 'Brak zalogowanego konta. Zaloguj się, aby zamówić.';
                }
                ?>
            </div>
            <br/>
            <div class="checkout-wrap">
                <ul class="checkout-bar">
                    <li class="previous visited">
                        <a href="koszyk.php">Koszyk</a>
                    </li>
                    <li class="active">
                        <a href="platnosc.php">Płatność</a>
                    </li>
                    <li class="next">
                        <label style=" position: relative; margin: 1px 62px;">Finalizacja</label>
                    </li>
                </ul>
            </div>
        </div>
        <div style="display: block; margin: 12px auto; padding: 10px 24px; background-color: #fff;">
            <h1>Wybierz metodę płatności:</h1>
            <label>
                Dotpay
            </label>
            <span class="btn"><a style="color: #fff; text-decoration: none;" href="finalizacja.php">Zakończ</a></span>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>