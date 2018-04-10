<?php
/*
 * User: Cezary
 * Date: 29.04.2017
 * Time: 14:30
 */

if (!isset($_SESSION)) {
    session_start();
}
ob_start();

if (!isset($_SESSION['akr']))
{
    $_SESSION['akr'] = array();
}

require 'polacz.php';
$_SESSION['astr'] = "koszyk";

if (!isset($_SESSION['email'])) {
    $_SESSION['e_login'] = 'Brak zalogowanego konta. Zaloguj się, aby zamówić.<br/>';
}
else {
    unset($_SESSION['e_login']);
}

if (isset($_POST['sp'])) {
    $_SESSION['sp'] = $_POST['sp'];
}
if (isset($_POST['sw'])) {
    $_SESSION['sw'] = $_POST['sw'];
}

if (isset($_POST['btnkod'])) {
    $k = mysqli_query($pol, 'SELECT * FROM kr WHERE kod = "' . $_POST['krkod'] . '" ');
    $wk = mysqli_fetch_object($k);
    if (mysqli_num_rows($k) > 0) {
        if ($wk->status == "Aktywny") {
            if (!czywtab($_SESSION['akr'], $_POST['krkod'])) {
                $_SESSION['akr'][] = $_POST['krkod'];
                header("Location: koszykf.php?kr=".$_POST['krkod']);
            } else {
                $_SESSION['e_kod'] = "Kod rabatowy został już użyty - " . $_POST['krkod'] . "<br/>";
            }
        } else {
            $_SESSION['e_kod'] = "Kod rabatowy stracił ważność - " . $_POST['krkod'] . "<br/>";
        }
    } else {
        $_SESSION['e_kod'] ="Błędny kod rabatowy - " . $_POST['krkod'] . "<br/>";
    }
}

$kwaga = 0.00;

function czywtab($tab, $ele)
{
    $w = false;
    for ($i = 0; $i < count($tab); $i++) {
        if ($tab[$i] == $ele) $w = true;
    }
    return $w;
}

function aktualizujmagazyn($k)
{
    require "polacz.php";
    for ($i = 0; $i < count($k); $i++) {
        if (substr($k[$i]->id, 0, 1) != "p" || substr($k[$i]->id, 0, 1) != "d") {
            mysqli_query($pol, "UPDATE produkty SET dostep = dostep - " . $k[$i]->sztuk . " WHERE id=" . $k[$i]->id);
        }
    }
}

function sm($k)
{

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Koszyk</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css"/>
    <script type="text/javascript" src="skrypt.js"></script>
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
<body>
<?php
require 'pasek1.php';
?>
<div id="kontener">
    <br/>
    <?php require 'belka.php'; ?>
    <?php require 'menu.php'; ?>
    <div id="zawartosc">
        <div style="display: block; margin: 12px auto; padding: 10px 24px; background-color: #fff;">
                <?php
                if (isset($_SESSION['e_login'])) {
                    echo '<div class="error">'.$_SESSION['e_login'].'</div>';
                }
                ?>
            <br/>
            <div class="checkout-wrap">
                <ul class="checkout-bar">
                    <li class="active">
                        <a href="koszyk.php">Koszyk</a>
                    </li>
                    <li class="next">
                        <label style=" position: relative; margin: 1px 62px;">Płatność</label>
                    </li>
                    <li class="">
                        <label style=" position: relative; margin: 1px 62px;">Finalizacja</label>
                    </li>
                </ul>
            </div>
        </div>
        <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff;">
                <?php
                if (isset($_SESSION['e_koszyk'])) {
                    echo '<div class="error">'.$_SESSION['e_koszyk'].'</div>';
                }
                ?>
            <form method="post" action="koszykf.php?wyczysc">
                <input type="submit" class="btn" value="Wyczyść koszyk" name="wk" />
            </form>
            <br/><br/>
            <?php
                //echo print_r($_SESSION['akr']);
            ?>
            <table class="tabela" border="0" cellspacing="4" cellpadding="8">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;Produkt&nbsp;</th>
                    <th>&nbsp;Cena / szt.&nbsp;</th>
                    <th>&nbsp;Sztuk&nbsp;</th>
                    <th>&nbsp;Całość&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_SESSION['koszyk'])) {
                    if (count($koszyk) < 1) {
                        echo '<tr><td colspan="6" align="center">Koszyk jest pusty :(</td></tr>';
                    } else {
                        for ($i = 0; $i < count($koszyk); $i++) {
                            if (isset($koszyk[$i]))
                            {
                                $kwaga += ($koszyk[$i]->sztuk * $koszyk[$i]->waga);
                                echo '<tr>';
                                echo '<td>&nbsp;';
                                $rez = mysqli_query($pol, "SELECT * FROM obr WHERE id_prod=" . ($koszyk[$i]->id) . " ORDER BY id ASC LIMIT 1");
                                if (is_numeric(substr($koszyk[$i]->id, 0 , 1)) && mysqli_num_rows($rez) > 0)
                                {
                                    $z = mysqli_fetch_object($rez);
                                    echo '<img alt="z1" style="margin: -4px;" width="48" height="48" src="panelCMS/pimg/' . $z->nazwa . '" />';
                                }
                                else {
                                    echo '<img alt="z1" style="margin: -4px;" width="48" height="48" src="ikon/prod.png" />';
                                }
                                echo '&nbsp;</td>';
                                $rez = NULL;
                                echo '<td><label><a href="produkt.php?idx=' . ($koszyk[$i]->id) . '">' . ($koszyk[$i]->nazwa) . '</a></label></td>';
                                echo '<td><label>' . number_format($koszyk[$i]->cena, 2, ',', ' ') . ' zł</label></td>';
                                echo '<td>';
                                if (substr($koszyk[$i]->id,0,1) != "p" && substr($koszyk[$i]->id,0,1) != "d" && substr($koszyk[$i]->id,0,2) != "kr") {
                                    echo '<label> ' . $koszyk[$i]->sztuk . ' </label><br/>';
                                    echo '<a class="btn" href="koszykf.php?ak&idep=' . ($koszyk[$i]->id) . '&szt=1">+</a>';
                                    echo '<a class="btn" href="koszykf.php?ak&idep=' . ($koszyk[$i]->id) . '&szt=-1"">&#8722;</a>';
                                } else {
                                    echo '<label>' . $koszyk[$i]->sztuk . '</label>';
                                }
                                echo '</td>';
                                echo '<td><label>' . number_format($koszyk[$i]->cena * $koszyk[$i]->sztuk, 2, ',', ' ') . ' zł</label></td>';
                                echo '<td>';
                                if (substr($koszyk[$i]->id,0,1) != "p") {
                                    echo '<label><a href="koszyk.php?idu=' . ($i) . '" class="abtn" title="Usuń produkt z koszyka"><img src="ikon/usun.png" alt="usuń" width="16" height="16" /></a></label>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    }
                } else {
                    echo '<tr><td colspan="6" align="center">Koszyk jest pusty :(</td></tr>';
                }
                ?>
                </tbody>
            </table>
            <br/>
        </div>
        <form action="" method="post">
            <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff;">
                    <?php
                    if (isset($koszyk) && count($koszyk) > 0) {
                        if (!isset($_SESSION['sw']) || !isset($_SESSION['sp'])) {
                            echo '<div class="error">'."Nie wybrano sposobu płatności lub wysyłki.</div><br/>";
                        }
                    }
                    ?>
                <h2>Wysyłka:&nbsp;</h2>
                <?php
                if (isset($_POST['sw']) && isset($kwaga) && isset($koszyk) && count($koszyk) > 0) {
                    $_SESSION['sw'] = $_POST['sw'];
                    $_SESSION['sp'] = $_POST['sp'];
                }
                $ck = array("", "");
                $i = 0;
                $wys;
                $twy = mysqli_query($pol, "SELECT * FROM wysylka WHERE wagamin <= " . $kwaga . " AND wagamax >=" . $kwaga . "");
                while ($owy = mysqli_fetch_object($twy)) {
                    if (isset($_SESSION['sw'])) {
                        if ($_SESSION['sw'] == "DPD") {
                            $ck[0] = "checked";
                            $ck[1] = "";
                            $ck[2] = "";
                        }
                        if ($_SESSION['sw'] == "Odbiór osobisty") {
                            $ck[0] = "";
                            $ck[1] = "checked";
                            $ck[2] = "";
                        }
                        if ($_SESSION['sw'] == "InPost"){
                            $ck[0] = "";
                            $ck[1] = "";
                            $ck[2] = "checked";
                        }
                    } else {
                        $ck[0] = "";
                        $ck[1] = "";
                    }
                    if ($owy->typ == "InPost")
                    {
                        echo '<label><input type="radio" name="sw" value="' . $owy->typ . '"' . $ck[$i] . ' /> Paczkomaty ' . ($owy->typ . ' ' . number_format($owy->cena,2,',',' ')) . ' zł</label> ';
                        if (isset($_SESSION['paczkomat']))
                        {
                            if (!empty($_SESSION['paczkomat'])) {
                                echo '';
                            }
                        }
                        else{
                            echo '<button class="btn">Wybierz paczkomat</button>';
                        }
                    }
                    else {
                        echo '<label><input type="radio" name="sw" value="' . $owy->typ . '"' . $ck[$i] . ' /> ' . ($owy->typ . ' ' . number_format($owy->cena,2,',',' ')) . ' zł</label>';
                    }
                    $i++;
                }
                ?>
                <h2>Płatność:&nbsp;</h2>
                <label><input type="radio" class="rb" name="sp" value="1" <?php
                    if (isset($_SESSION['sp'])) {
                        if ($_SESSION['sp'] == 1) {
                            echo 'checked';
                        }
                    }
                    ?>/> Gotówka</label>
                <label><input type="radio" class="wb" name="sp" value="2" <?php
                    if (isset($_SESSION['sp'])) {
                        if ($_SESSION['sp'] == 2) {
                            echo 'checked';
                        }
                    }
                    ?>/> Przelew</label>
                <label><input type="radio" name="sp" value="3" <?php
                    if (isset($_SESSION['sp'])) {
                        if ($_SESSION['sp'] == 3) {
                            echo 'checked';
                        }
                    }
                    ?>/> Karta</label>
                <br/><br/>
                <input class="btn" type="submit" name="wp" value="Wybierz"/>
                <?php
                if (isset($_SESSION['sw']) && isset($_SESSION['sp'])) {
                    echo '<input class="btn" type="submit" name="uwp" value="Usuń wybór"/>';
                }
                if (isset($_POST['wp']))
                {
                    header("Location: koszykf.php?tw=".$_SESSION['sw']."&w=".$kwaga);
                }
                if (isset($_POST['uwp']))
                {
                    unset($_SESSION['sp']);
                    unset($_SESSION['sw']);
                    for ($i = 0; $i < count($koszyk); $i++) {
                        if ($koszyk[$i]->nazwa == "DPD" || $koszyk[$i]->nazwa == "InPost" || $koszyk[$i]->nazwa == "Odbiór osobisty") {
                            header("Location: koszyk.php?idu=" . $i);
                        }
                    }
                }
                ?>
            </div>
            <div style="margin: 8px auto; padding: 16px 24px; background-color: #fff; display: inline-table; position: relative; width: 100%;">
                <h2>Dodatkowe usługi:</h2>
                <?php
                $result = mysqli_query($pol, "SELECT * FROM dodatkowe ORDER BY id ASC");
                if (mysqli_num_rows($result) > 0) {
                    while ($dod = mysqli_fetch_object($result)) {
                        if ($dod->cena > 0) {
                            echo '<div class="produkt" style="height: 270px;" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?iddod=' . $dod->id . '\';" style="cursor:pointer;" >';
                            echo '<div class="etykp"><a href="produkt.php?iddod=' . ($dod->id) . '" >' . ($dod->nazwa) . '</a><br /><br/>';
                            if ($dod->scena == NULL) {
                                echo '<span class="cena">' . number_format($dod->cena, 2, ',', '.') . ' zł</span><br/>';
                            } else {
                                echo '<span class="scena">' . number_format($dod->scena, 2, ',', '.') . ' zł</span><br/>';
                                echo '<span class="acena">' . number_format($dod->cena, 2, ',', '.') . ' zł</span><br/>';
                            }
                            echo '<span><a class="dk" href="koszyk.php?iddod=' . ($dod->id) . '">Do koszyka</a></span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                }
                ?>
                <br/>
            </div>
            <div style="margin: 12px auto; padding: 10px 24px; background-color: #fff;">
                <?php
                if (isset($_SESSION['e_kod']))
                {
                    echo '<div class="error">'.$_SESSION['e_kod'].'</div>';
                }
                ?>
                <h2>Kod rabatowy:&nbsp;</h2>
                <input class="tb" type="text" name="krkod" style="width: 81%;">&nbsp;
                <input class="btn" value="Użyj kodu" type="submit" name="btnkod">
            </div>
            <div style="margin: 12px auto; padding: 10px 24px; background-color: #fff;">
                <h2>Uwagi do zamówienia:</h2>
                <textarea class="tb" style="width: 100%;" name="uwagi"></textarea>
            </div>
            <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff;">
                    <?php
                    if (isset($koszyk) && count($koszyk) > 0) {
                        if (!isset($_SESSION['sw']) || !isset($_SESSION['sp'])) {
                            if (isset($_SESSION['e_koszyk']))
                            {
                                if (strpos($_SESSION['e_koszyk'], 'Nie wybrano sposobu płatności lub wysyłki.<br/>'))
                                {
                                    $_SESSION['e_koszyk'] .= 'Nie wybrano sposobu płatności lub wysyłki.<br/>';
                                }
                            }
                        }
                    }
                    ?>
                <h1>Podsumowanie zamówienia: </h1>
                <table border="0">
                    <tbody>
                    <tr>
                        <td>Wartość koszyka:&nbsp;</td>
                        <td>
                            <?php
                            $cw = 0.00;
                            if (isset($_SESSION['koszyk'])) {
                                if (isset($_SESSION['sw'])) {
                                    $koszyk = unserialize(serialize($_SESSION['koszyk']));
                                    for ($i = 0; $i < count($koszyk); $i++) {
                                        if (substr($koszyk[$i]->id, 0, 1) == "p") {
                                            $cw = $koszyk[$i]->cena;
                                        }
                                    }
                                }
                            }
                            echo number_format(wartosckoszyka() - $cw, 2, ',', ' ') . ' zł';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Koszt dostawy:&nbsp;</td>
                        <td>
                            <?php
                            if (isset($_SESSION['sw'])) {
                                if ($cw <= 0) echo number_format(0, 2, ',', ' ') . ' zł';
                                else echo number_format($cw, 2, ',', ' ') . ' zł';
                            } else {
                                echo 'Proszę wybrać sposób dostawy';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Rabat:&nbsp;</td>
                        <td>
                            <?php
                            $r = 0.00;
                            if (isset($_SESSION['koszyk'])) {
                                for ($i = 0; $i < count($koszyk); $i++) {
                                    if ($koszyk[$i]->cena < 0) $r = $koszyk[$i]->cena * -1;
                                }
                            }
                            echo number_format($r, 2, ',', ' ') . ' zł';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Suma zamówienia:&nbsp;</td>
                        <td>
                            <?php
                            echo number_format(wartosckoszyka(), 2, ',', ' ') . ' zł';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Typ płatności:&nbsp;</td>
                        <td>
                            <?php
                            if (isset($_SESSION['sp'])) {
                                switch ($_SESSION['sp']) {
                                    case 1:
                                        echo 'Gotówka';
                                        break;
                                    case 2:
                                        echo 'Przelew';
                                        break;
                                    case 3:
                                        echo 'Karta';
                                        break;
                                    default :
                                        echo '';
                                        break;
                                }
                            } else {
                                echo 'Proszę wybrać typ płatności';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><i><b>Koszt płatności:</b></i></td>
                        <td>
                            <i><b>
                                    <?php
                                    $kp = 0.00;
                                    echo number_format($kp, 2, ',', ' ') . ' zł';
                                    ?>
                                </b></i>
                        </td>
                    </tr>
                    <tr><td colspan="2"><br/></td></tr>
                    <tr>
                        <td><big><b>Do zapłaty: &nbsp</b></big></td>
                        <td><big>
                                <?php
                                if (isset($_SESSION['sw']) && isset($_SESSION['sp'])) {
                                    echo '<b>' . number_format(wartosckoszyka() + $kp, 2, ',', ' ') . ' zł</b>';
                                } else {
                                    echo '<b>' . number_format(0, 2, ',', ' ') . ' zł</b>';
                                }
                                ?>
                            </big>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br/>
                <?php
                if (isset($_SESSION['idk']) && count($koszyk) > 0 && isset($_SESSION['sp']) && isset($_SESSION['sw'])) {
                    echo '<input type="submit" name="dalej" class="btn" value="Dalej"/>';
                }
                else
                {
                    echo '<input type="submit" name="dalej" class="btn" value="Dalej" title="Wykryto błędy w składaniu zamówienia. Odnajdź czerwone pola i napraw błędy." disabled/>';
                }
                if (isset($_POST['dalej'])) {
                    $kdb = serialize($_SESSION['koszyk']);
                    //sprawdzić obecny stan
                    if (mysqli_query($pol, "INSERT INTO lz VALUES(NULL," . $_SESSION['idk'] . ",'" . ($kdb) . "',NOW(),DEFAULT," . $_POST['sp'] . ",\"" . $_POST['uwagi'] . "\")")) {
                        $koszyk = unserialize(serialize($_SESSION['koszyk']));
                        aktualizujmagazyn($koszyk);
                        unset($koszyk);
                        $koszyk = null;
                        unset($_SESSION['koszyk']);
                        if (isset($_SESSION['sp']))
                        {
                            if ($_SESSION['sp'] != 1){
                                header("Location: platnosc.php");
                            }
                            else
                            {
                                header("Location: finalizacja.php");
                            }
                        }
                    } else {
                        echo 'Błąd ' . mysqli_error($pol);
                    }
                }
                ?>
            </div>
        </form>
    </div>

    <br/>

</div>
<?php require 'stopka.php'; ?>
<button onclick="topFunction();" id="myBtn" title="W górę strony"><img src="ikon/aup.png"></button>
</body>
</html>