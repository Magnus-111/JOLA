<?php

if (!isset($_SESSION)) {
    session_start();
}
ob_start();

if (isset($_SESSION['email'])) {
    $_SESSION['astr'] = "konto";
} else {
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Konto klienta</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css" />
    <script type="text/javascript" src="skrypt.js" ></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

</head>
<body>
<?php
require 'pasek1.php';
?>
<div id="kontener">
    <br/>
    <?php
        require 'belka.php';
        require 'menu.php';
        $q = mysqli_query($pol, "SELECT * FROM lkk WHERE id_uzyt=".$_SESSION['idk']." ");
        $rez = mysqli_query($pol, 'SELECT * FROM lak WHERE id_uzyt=' . $_SESSION['idk']." ");
    ?>
    <div id="zawartosc">
        <h1>Konto klienta: <?php echo $_SESSION['email']; ?></h1>
        <div class="tab" >
            <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'dane');" id="ldane">Twoje dane</a>
            <!--<a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'adresy');">Adresy</a>-->
            <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'zam');" id="wz">Zamówienia</a>
            <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'riz');" id="lriz">Reklamacje i zwroty</a>
            <a href="javascript:void(0);" class="tablinks" onclick="otworztab(event, 'ust');" id="ustk">Ustawienia konta</a>
        </div>
        <div id="dane" class="tabcontent">
            <?php
            $lk = mysqli_fetch_object($q);
            while ($konto = mysqli_fetch_object($rez)) {
                if (!isset($_POST['zaw']) && !isset($_POST['zaf']) && !isset($_POST['ddf']) && !isset($_POST['znt']))
                {
                    //$ad = str_replace("", "<br/>", $konto->adres);
                    $ad = $konto->adres;
                    //$adf = str_replace("  ", "<br/>", $konto->adresf);
                    $adf = $konto->adresf;
                    if ($lk->aktywne == false)
                    {
                        echo '<div class="error">!!! Twoje konto nie zostało aktywne. &nbsp;<a href="#" style="color: #8B0000; font-weight: bolder; font-style: italic;">Kliknij tutaj</a>, by aktywować !!!</div>';
                    }
                    echo '<h4>Numer klienta: </h4><label>'.$konto->id.'</label><h4>Imię:</h4><label>' . $konto->imie . '</label><h4>Nazwisko:</h4><label>' . $konto->nazwisko . '</label><h4>Telefon:</h4><label>' . $konto->telefon .
                        '</label><form action="" method="post"><input class="btn" name="znt" style="margin: 6px 4px;" type="submit" value="Zmień numer telefonu" ></form><br/><h4>Adres do wysyłki:</h4><label>' . $ad . '</label>';
                    echo '<form action="" method="post"><input class="btn" name="zaw" style="margin: 6px 4px;" type="submit" value="Zmień adres do wysyłki" ></form>';
                    if (!empty($adf)) {
                        echo '<h4>Dane do faktury:</h4><label>' . $adf . '</label>';
                        echo '<form action="" method="post"><input class="btn" name="zaf" style="margin: 6px 4px;" type="submit" value="Zmień adres do faktury" ></form>';
                    } else {
                        echo '<h4>Dane do faktury:</h4><label>Brak adresu, dodaj przez naciśnięcie poniższego przycisku.</label><br/>';
                        echo '<form action="" method="post"><input class="btn" name="ddf" style="margin: 6px 4px;" type="submit" value="Dodaj dane do faktury" ></form>';
                    }
                }
                elseif (isset($_POST['znt']))
                {
                    include 'zt.php';
                }
                elseif (isset($_POST['zaw']))
                {
                    include 'zad.php';
                }
                elseif (isset($_POST['ddf']) || isset($_POST['zaf']))
                {
                    include 'zaf.php';
                }
            }
            ?>
        </div>
        <script type="text/javascript"> document.getElementById("ldane").click();</script>
        <div id="zam" class="tabcontent" style=" padding: 0px; ">
            <div class="tab" >
                <a href="javascript:void(0);" class="tablinks" onclick="otworztabs(event, 'zam','zr');" id="lzr">Zamówienia realizowane</a>
                <a href="javascript:void(0);" class="tablinks" onclick="otworztabs(event, 'zam','zz');" id="lzz">Zamówienia zakończone</a>
                <a href="javascript:void(0);" class="tablinks" onclick="otworztabs(event, 'zam','za');" id="lza">Zamówienia anulowane</a>
            </div>
            <div id="zr" class="tabcontent" style="margin: 0;">
                <h3>Twoje zamówienia w realizacji</h3>
                <?php include 'zr.php'; ?>
            </div>
            <div id="zz" class="tabcontent" style="margin: 0;">
                <h3>Twoje zakończone zamówienia</h3>
                <?php include 'zz.php'; ?>
            </div>
            <div id="za" class="tabcontent" style="margin: 0;">
                <h3>Twoje anulowane zamówienia</h3>
                <?php include 'za.php'; ?>
            </div>
        </div>
        <?php
        if (isset($_GET['idz']))
        {
            if (is_numeric($_GET['idz']))
            {
                echo '<script type="text/javascript"> document.getElementById("lzr").click();</script>';
            }
        }
        ?>
        <div id="riz" class="tabcontent">
            <?php include 'rma.php'; ?>
        </div>
        <div id="ust" class="tabcontent">
            <form method="post">
                <?php
                require 'polacz.php';
                $q = mysqli_query($pol, "SELECT * FROM lkk WHERE id_uzyt=".$_SESSION['idk']." ");
                $oq = mysqli_fetch_object($q);
                    echo '<h4>Adres e-mail:</h4>';
                    echo $oq->email;
                    echo '<br/><label>Nowe e-mail: </label><input class="tb" type="text" placeholder="Wprowadź nowy e-mail" name="ne" />';
                    echo '<h4>Hasło:</h4>';
                    echo '<label>Nowe hasło: </label><input class="tb" type="password" placeholder="Wprowadź nowe hasło" name="nh" />';
                    echo '<h4>Newsletter:</h4>';
                    if ($oq->newsletter == 0)
                    {
                        echo 'Nie zezwolono. &nbsp;&nbsp;';
                        echo '<input type="submit" class="btn" value="Zezwól na newsletter" name="znn" />';
                    }
                    else
                    {
                        echo 'Zezwolono. &nbsp;&nbsp;';
                        echo '<input type="submit" class="btn" value="Anuluj newslettery" name="znn" />';
                    }
                ?>
                <br/><br/>
                <input type="submit" class="btn" value="Zapisz ustawienia" />
            </form>
            <?php
            if (isset($_POST['znn']))
            {
                $o = mysqli_fetch_object(mysqli_query($pol,"SELECT * FROM lkk WHERE id_uzyt=".$_SESSION['idk']));
                if ($o->newsletter == 0)
                {
                    mysqli_query($pol,"UPDATE lkk SET newsletter = 1 WHERE id_uzyt=".$_SESSION['idk']);
                }
                else
                {
                    mysqli_query($pol,"UPDATE lkk SET newsletter = 0 WHERE id_uzyt=".$_SESSION['idk']);
                }
                echo "<script type='text/javascript'> $('ustk').click();</script>";
            }
            if (isset($_POST['ne']))
            {
                if (!empty($_POST['ne']))
                {
                    mysqli_query($pol,"UPDATE lkk SET email = '".$_POST['ne']."' WHERE id_uzyt=".$_SESSION['idk']);
                }
                header("Location: ");
                echo "<script type='text/javascript'> $('ustk').click();</script>";
            }
            if (isset($_POST['nh']))
            {
                if (!empty($_POST['nh']))
                {
                    mysqli_query($pol,"UPDATE lkk SET haslo = '".password_hash($_POST['nh'],PASSWORD_DEFAULT)."' WHERE id_uzyt=".$_SESSION['idk']);
                }
                header("Location: ");
                echo "<script type='text/javascript'> $('ustk').click();</script>";
            }
            ?>
        </div>

    </div>
</div>
<?php
require 'stopka.php';

if (isset($_GET['idz']))
{
    urldecode($_GET['idz']);
}
?>
<div id="prv-billboard"></div>
</body>
</html>