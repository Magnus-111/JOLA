<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['astr'])|| $_SESSION['astr'] != "index")
{
    $_SESSION['astr'] = "index";
}
require 'polacz.php';

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Strona główna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styl.css" />
    <script type="text/javascript" src="skrypt.js" ></script>
    <script type="text/javascript" >
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 220 || document.documentElement.scrollTop > 220) {
                document.getElementById("myBtn").style.display = "block";
            } else {
                document.getElementById("myBtn").style.display = "none";
            }
        }
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body onload="currentSlide(slideIndex);">
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div class="slideshow-container" style = "height: 273px;">
            <div class="slideshow">
                <?php
                $ilezdj = 0;
                $wzdj = 0;
                $rez = mysqli_query($pol, 'SELECT * FROM promocje');
                while ($z = mysqli_fetch_object($rez)) {
                    if (!empty($z->baner)) {
                        $wzdj++;
                    }
                }
                $rez = mysqli_query($pol, 'SELECT * FROM promocje');
                while ($z = mysqli_fetch_object($rez)) {
                    if (!empty($z->baner)) {
                        $ilezdj++;
                        echo '<a href="promocja.php?idpromo='.$z->id.'"><div class="mySlides fade">'
                            . '<div class="numbertext">' . $ilezdj . ' / ' . $wzdj . '</div>'
                            . '<img style="width: 100%; height: 100%; margin: 0 auto;" alt="z1" src="panelCMS/pimg/' . $z->baner . ' "  >'
                            . '<div class="slidetext">'.$z->tytul.'</div>'
                            . '</div></a>';
                    }
                }
                ?>
                <span class="gprev" onclick="plusSlides(-1);">❮</span>
                <span class="gnext" onclick="plusSlides(1);">❯</span>
            </div>
            <div style="position: relative; margin: 2px; text-align: center;">
                <?php
                for ($i = 1; $i <= $ilezdj; $i++) {
                    echo '<span class="dot" onclick="currentSlide(' . $i . ')"></span>';
                }
                ?>
            </div>
            <script type="text/javascript">carousel();</script>
        </div>
        <br/>
        <div id="np">
            <div style="position:relative; display: table; margin: 10px 0px; width: 100%;">
                <h1>Nowości</h1>
                <?php
                 require 'nowosci.php';
                ?>
            </div>
            <div style="position: relative; display: block; margin: 10px 0px;">
                <h1>Przecenione produkty</h1>
                <?php
                require "przecenione.php";
                /*$pp = mysqli_query($pol, 'SELECT * FROM `produkty` WHERE scena <> ""');
                if (mysqli_num_rows($pp) > 0)
                {
                    while ($opp = mysqli_fetch_object($pp)) {
                        echo '<div class="produkt" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx='.$opp->id.'\';" >';
                        echo '<div class="minimg" >';
                        $rezp = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $opp->id. ' ORDER BY id DESC');
                        if ($rezp) {
                            while ($zp = mysqli_fetch_object($rezp)) {
                                echo '<img alt="z1" src="panelCMS/pimg/' . $zp->nazwa . ' "  >';
                                break;
                            }
                        } else {
                            echo '<img alt="z1" src="../ikon/prod.png">';
                        }
                        echo '</div>';
                        echo '<div class="etykp"><a href="produkt.php?idx=' . ($opp->id) . '" >' . ($opp->nazwa) . '</a><br />';
                        //echo $produkt->opis . '<br>';
                        echo 'Dostępność: ' . $opp->dostep . ' szt<br />';
                        if ($opp->scena == NULL) {
                            echo '<span class="cena">' . number_format($opp->cena, 2, ',', '.') . ' zł</span><br/>';
                        } else {
                            echo '<span class="scena">' . number_format($opp->scena, 2, ',', '.') . ' zł</span><br/>';
                            echo '<span class="acena">' . number_format($opp->cena, 2, ',', '.') . ' zł</span><br/>';
                        }
                        echo '<span><a class="dk" href="koszyk.php?id=' . ($opp->id) . '">Do koszyka</a></span>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                else
                {
                    echo '<h4 style="display: inline-table; text-align: center; min-width: 100%;">Brak przecenionych produktów.</h4>';
                }*/
                ?>
            </div>
            <br/>
        </div>
    </div>
    <br/>
</div>
<?php include 'stopka.php'; ?>
<button onclick="topFunction();" id="myBtn" title="W górę"><img src="ikon/aup.png"></button>
<div id="prv-billboard"></div>
</body>
</html>