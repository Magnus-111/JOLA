<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();

$_SESSION['astr'] = "platnosc";

require 'polacz.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Zamówienie złożone</title>
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
                        <label style=" position: relative; margin: 1px 72px;">Koszyk</label>
                    </li>
                    <li class="previous visited">
                        <label style=" position: relative; margin: 1px 68px;">Płatność</label>
                    </li>
                    <li class="active">
                        <label style=" position: relative; margin: 1px 52px;">Finalizacja</label>
                    </li>
                </ul>
            </div>
        </div>
        <div style="display: block; margin: 12px auto; padding: 10px 24px; background-color: #fff;">
            <h1>Podsumowanie zamówienia:</h1>
            <h3>Numer twojego zamówienia: </h3>
            <h2>
                <?php
                $q = mysqli_query($pol, 'SELECT * FROM lz WHERE id_uzyt='.$_SESSION['idk'].' AND status ="złożone" ORDER BY id DESC LIMIT 1');
                $z = mysqli_fetch_object($q);
                echo '<a href="konto.php?idz='.$z->id.'">'.$z->id.'</a>';
                ?>
            </h2>
            <h3>Przewidywana data przygotowania zamówienia/wysłania zamówienia: </h3>
            <?php
            $data = '';
            $swieta = array('01 Jar','06 Jar','01 May','02 May','03 May','15 Aug','01 Nov','11 Nov','24 Dec','25 Dec','26 Dec');
            $miesiace = array('Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Czer', 'Lip', 'Sie', 'Wrz', 'Paź', 'Lis', 'Gru');
            $data = date('D d M Y', strtotime(date('d-m-Y') . '+ 3 days'));
            for ($i = 0; $i < count($swieta);$i++)
            {
                if (substr($data,4,6) == $swieta[$i])
                {
                    $data = date('D d M Y', strtotime($data . '+ 3 days'));
                }
            }
            if (substr($data,0,3) == "Sat")
            {
                $data = date('D d M Y', strtotime($data . '+ 3 days'));
            }
            if (substr($data,0,3) == "Sun")
            {
                $data = date('D d M Y', strtotime($data . '+ 2 days'));
            }
            if (substr(date('D d M Y', strtotime(date('d-m-Y'))), 0,3) == "Fri")
            {
                $data = date('D d M Y', strtotime($data . '+ 1 days'));
            }

            $data = str_replace('Mon', 'Pon',$data);
            $data = str_replace('Tue', 'Wt',$data);
            $data = str_replace('Wed', 'Śro',$data);
            $data = str_replace('Thu', 'Czw',$data);
            $data = str_replace('Fri', 'Pt',$data);
            $data = str_replace('Sat', 'Sob',$data);
            $data = str_replace('Sun', 'Nied',$data);
            $data = str_replace('Jar', 'Sty',$data);
            $data = str_replace('Feb', 'Lut',$data);
            $data = str_replace('Apr', 'Kwi',$data);
            $data = str_replace('May', 'Maj',$data);
            $data = str_replace('Jun', 'Cze',$data);
            $data = str_replace('Jul', 'Lip',$data);
            $data = str_replace('Aug', 'Sie',$data);
            $data = str_replace('Sep', 'Wrz',$data);
            $data = str_replace('Oct', 'Paź',$data);
            $data = str_replace('Nov', 'Lis',$data);
            $data = str_replace('Dec', 'Gru',$data);
            echo '<h2>'.$data.'</h2>';
            ?>
            <br/><br/>
            <img alt="hs" style="display: block; position: relative; margin: 4px auto;" src="ikon/headset.png" />
            <h3 style="display: table; position: relative; margin: 0 auto;">Masz pytania? Zadzwoń </h3>
            <h4 style="display: table; position: relative; margin: 0 auto;">Telefon: <a href="tel:+48000000000">+48 000 000 000</a></h4>
            <h4 style="display: table; position: relative; margin: 0 auto;">E-mail: <a href="mailto:kontakt@d.pl">kontakt@d.pl</a></h4>
            <br/><br/>
            <big>
                <a href="regulamin.php">Regulamin sklepu</a> &nbsp; &nbsp;
                <a href="">Odstąpienie od umowy</a>
            </big>
            <br/><br/>
            <span class="btn"><a style="color: #fff; text-decoration: none;" href="index.php">Koniec</a></span>
            <br/><br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>