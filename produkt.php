<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['astr'] = "produkt";
$tnazwa = NULL;
$tdostep = NULL;
$tcena = NULL;
$tscena = NULL;
$topis = NULL;
$twaga = NULL;
$tid = NULL;
$tzdj = TRUE;
$_SESSION['astr'] = "produkt.php";
if (isset($_GET['idx'])) {
    require 'polacz.php';
    $rezult = mysqli_query($pol, 'SELECT * FROM produkty WHERE id=' . $_GET['idx'] . '');
    while ($produkt = mysqli_fetch_object($rezult)) {
        $tid = $produkt->id;
        $tnazwa = $produkt->nazwa;
        $tdostep = $produkt->dostep;
        $tscena = $produkt->scena;
        $tcena = $produkt->cena;
        $topis = $produkt->opis;
        $twaga = $produkt->waga;
        $tmaterial = $produkt->material;
        $tzdj = TRUE;
    }
}

if (isset($_GET['iddod'])) {
    require 'polacz.php';
    $rezult = mysqli_query($pol, 'SELECT * FROM dodatkowe WHERE id=' . $_GET['iddod'] . '');
    while ($produkt = mysqli_fetch_object($rezult)) {
        $tid = $produkt->id;
        $tnazwa = $produkt->nazwa;
        $tdostep = $produkt->dostep;
        $tscena = $produkt->scena;
        $tcena = $produkt->cena;
        $topis = $produkt->opis;
        $twaga = $produkt->waga;
        $tzdj = FALSE;
    }
}

function snm($idp)
{
    require 'polacz.php';
    $q = mysqli_query($pol,'SELECT * FROM produkty WHERE id='.$idp);
    $r = mysqli_fetch_object($q);
    if ($r->dostep > 0)
    {
        return true;
    }
    return false;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styl.css">
    <script type="text/javascript" src="skrypt.js" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>.: JOLA - butik internetowy - produkt: <?php echo $tnazwa ?> :.</title>
</head>
<body onload="currentSlide(slideIndex);">
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div class="kartaproduktu">
            <h1><?php echo $tnazwa; ?></h1>
            <br/>
            <div class="opis">
                <?php
                if ($tzdj) {
                    if ($tdostep < 1) {
                        echo '<div style="margin: 4px 0px;"><img alt="d0" width="64" height="64" src="ikon/paczka0.png" /><span style=" display: table; position: relative; margin: -45px 4px 0 80px; font-style: italic;">Dostępnych sztuk: ' . $tdostep . '</span></div><br/>';
                    } else if ($tdostep < 3) {
                        echo '<div style="margin: 4px 0px;"><img alt="d0" width="64" height="64" src="ikon/paczka2.png" /><span style=" display: table; position: relative; margin: -45px 4px 0 80px; font-style: italic;">Dostępnych sztuk: ' . $tdostep . '</span></div><br/>';
                    } else {
                        echo '<div style="margin: 4px 0px;"><img alt="d0" width="64" height="64" src="ikon/paczka3.png" /><span style=" display: table; position: relative; margin: -45px 4px 0 80px; font-style: italic;">Dostępnych sztuk: ' . $tdostep . '</span></div><br/>';
                    }
                }
                echo '<br/>';
                if ($tscena == NULL) {
                    echo '<span class="cena">Cena: ' . number_format($tcena, 2, ',', '.') . ' zł</span><br/><br/>';
                } else {
                    echo '<span class="scena">Poprzednia cena: ' . number_format($tscena, 2, ',', '.') . ' zł</span><br/>';
                    echo '<span class="acena">Aktualna cena: ' . number_format($tcena, 2, ',', '.') . ' zł</span><br/><br/>';
                }
                echo '<span style="font-style: italic; font-weight: bold; margin: 4px 0px;">Materiał:  </span><span>'. $tmaterial .'</span><br/><br/>';
                echo '<span style="font-style: italic; font-weight: bold; margin: 4px 0px;">Opis: <br/></span><span>' . $topis . '</span><br/><br/><br/><br/>';

                if (snm($tid))
                {
                    echo '<span style="margin: 16px 33%;"><a class="dk" href="koszyk.php?id=' . ($tid) . '">Do koszyka</a></span></span>';
                }
                else
                {
                    echo '<span style="color: #ff7500; font-weight: bolder; font-size: large; font-style: italic;">Brak produktu na magazynie!!! Przepraszamy.</span>';
                }
                echo '';
                ?>
            </div>
            <?php
            if ($tzdj) {
                echo '<div class="slideshow-container" style = "width: 40%; float: left;">
                        <div class="slideshow">';
                $ilezdj = 0;
                $wzdj = 0;
                $rez = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $tid . ' ORDER BY id ASC');
                while ($z = mysqli_fetch_object($rez)) {
                    if (!empty($z->nazwa)) {
                        $wzdj++;
                    }
                }
                $rezz = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $tid . ' ORDER BY id ASC');
                while ($z = mysqli_fetch_object($rezz)) {
                    if (mysqli_num_rows($rezz) > 0) {
                        $ilezdj++;
                        echo '<div class="mySlides fade">'
                            . '<div class="numbertext">' . $ilezdj . ' / ' . $wzdj . '</div>'
                            . '<img style="width: 100%; height: 100%; margin: 0 auto;" alt="z1" src="panelCMS/pimg/' . $z->nazwa . ' " >'
                            . '</div>';
                    } else {
                        $ilezdj++;
                        echo '<div class="mySlides fade">'
                            . '<div class="numbertext">' . $ilezdj . ' / ' . $wzdj . '</div>'
                            . '<img style="width: 100%; height: 100%; margin: 0 auto;" alt="z1" src="ikon/prod.png" >'
                            . '</div>';
                    }
                }
                echo '<span class="gprev" onclick="plusSlides(-1);">❮</span>
                            <span class="gnext" onclick="plusSlides(1);">❯</span>
                        </div>
                        <div style="position: relative; margin: 6px 0; text-align: center;">';
                for ($i = 1; $i <= $ilezdj; $i++) {
                    echo '<span class="dot" onclick="currentSlide(' . $i . ')"></span>';
                }

                echo '</div><script type="text/javascript">carousel();</script></div>';
            } else {
                echo '<br/><br/>';
            }
            ?>

        </div>

    </div>
</div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>