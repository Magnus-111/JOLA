<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 17.03.2017
 * Time: 20:33
 */
if (!isset($_SESSION)) {
    session_start();
}


require 'polacz.php';

ob_start();

if (isset($_GET['idpromo']))
{
    $_SESSION['astr'] = "promocja";
    $q = mysqli_query($pol,'SELECT * FROM promocje WHERE id='.$_GET['idpromo']);
    if (mysqli_num_rows($q) < 1)
    {
        header("Location: index.php");
    }
}
else
{
    //header("Location: ".$_SESSION['astr']);
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
    <title>JOLA - Butik internetowy - Promocje</title>
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
        <div style="display: table; margin: 12px auto; padding: 16px 24px; background-color: #ffffff; width: 97%;">
            <?php
                $pr = mysqli_fetch_object($q);
                echo '<h1>'.$pr->tytul.'</h1>';
                echo '<img style=" width: 100%; margin: 5px auto;" src="panelCMS/pimg/'.$pr->baner.'" /><br/>';
                echo '<h2 style="margin: 2px auto; text-align: center;">'.$pr->opis.'</h2>';
                echo '<br/><h3 style="margin: 2px auto; text-align: center;">Produkty objęte promocją:</h3><br/>';
                $produkty = explode(";",$pr->id_prod);
                for ($i = 0; $i < count($produkty);$i++)
                {
                    $pzp = mysqli_query($pol,'SELECT * FROM produkty WHERE id='.$produkty[$i]);
                    $opzp = mysqli_fetch_object($pzp);
                    echo '<div class="produkt" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx=' . $opzp->id . '\';" >';
                    echo '<div class="minimg" >';
                    $rez = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $opzp->id . ' ORDER BY id ASC LIMIT 1');
                    if (mysqli_num_rows($rez) > 0) {
                        while ($z = mysqli_fetch_object($rez)) {
                            echo '<img alt="z1" src="panelCMS/pimg/' . $z->nazwa . ' " />';
                        }
                    } else {
                        echo '<img alt="z1" src="ikon/prod.png" />';
                    }
                    echo '</div>';
                    echo '<div class="etykp"><a style="font-size: 120%;" href="produkt.php?idx=' . ($opzp->id) . '" >' . ($opzp->nazwa) . '</a><br/><br/>';
                    if ($opzp->dostep < 1)
                    {
                        echo '<span style="font-style: italic; color: #ff0000; font-weight: 600;">Dostępność: ' . $opzp->dostep . ' szt</span><br />';
                    }
                    elseif ($opzp->dostep < 4)
                    {
                        echo '<span style="font-style: italic; color: #ff8900; font-weight: 600;">Dostępność: ' . $opzp->dostep . ' szt</span><br />';
                    }
                    else
                    {
                        echo '<span style="font-style: italic; font-weight: 600;">Dostępność: ' . $opzp->dostep . ' szt</span><br />';
                    }
                    if ($opzp->scena == NULL) {
                        echo '<span class="cena">' . number_format($opzp->cena, 2, ',', '.') . ' zł</span><br/>';
                    } else {
                        echo '<span class="scena">' . number_format($opzp->scena, 2, ',', '.') . ' zł</span><br/>';
                        echo '<span class="acena">' . number_format($opzp->cena, 2, ',', '.') . ' zł</span><br/>';
                    }

                    echo '</div>';
                    if (snm($opzp->id))
                    {
                        echo '<span><a class="dk" title="Naciśnij, aby dodać do koszyka" href="koszyk.php?id=' . ($opzp->id) . '">Do koszyka</a></span>';
                    }
                    else
                    {
                        echo '<span class="bp" title="Brak produktu na magazynie">Brak</span>';
                    }
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>

