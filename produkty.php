<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['astr'] = "produkty";
require 'polacz.php';

if (!isset($_POST['t'])) {
    $_POST['t'] = " ";
}
if (!isset($_POST['s'])) {
    $_POST['s'] = "A";
}
if (!isset($_POST['tk'])) {
    $_POST['tk'] = " ";
}
if (!isset($_POST['c1'])) {
    $_POST['c1'] = 0;
}
if (!isset($_POST['c2'])) {
    $_POST['c2'] = 0;
}
if (isset($_GET['zg'])) {
    if ($_GET['zg']) {
        $_POST['t'] = $_GET['zg'];
    }
}

if (isset($_POST['czyscf']) && ($_POST['t'] != " " || $_POST['tk'] != " " || $_POST['c1'] > 0 || $_POST['c2'] > 0)) {
    $_POST['t'] = " ";
    $_POST['tk'] = " ";
    $_POST['c1'] = 0;
    $_POST['c2'] = 0;
    header("Location: produkty.php");
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

function prod($t, $tk, $c1, $c2, $s)
{
    include 'polacz.php';
    $wh = '';
    $wt = '';
    $ws = '';
    $wtk = '';
    $wc = '';
    $a1 = '';
    $a2 = '';
    switch ($s) {
        case 'A':
            $ws = ' ORDER BY nazwa ASC';
            break;
        case 'Z':
            $ws = ' ORDER BY nazwa DESC';
            break;
        case 'R':
            $ws = ' ORDER BY cena ASC';
            break;
        case 'M':
            $ws = ' ORDER BY cena DESC';
            break;
        default:
            $ws = '';
            break;
    }
    switch ($t) {
        case "buty":
            $wt = ' `typ` = "buty"';
            break;
        case "torebki":
            $wt = ' `typ` = "torebki"';
            break;
        case "dodatki":
            $wt = ' `typ` = "dodatki"';
            break;
        case "portfele":
            $wt = ' `typ` = "portfele"';
            break;
        default:
            $wt = '';
            break;
    }
    switch ($tk) {
        case "Tkanina":
            $wtk = ' `material` = "Materiał"';
            break;
        case "Skora":
            $wtk = ' `material` = "Skóra"';
            break;
        case "Inny":
            $wtk = ' `material` = "Inny"';
            break;
        default:
            $wtk = '';
            break;
    }

    if ($c1 > 0 && $c2 == 0) {
        $wc = ' cena >= ' . $c1;
    } else if ($c2 > 0 && $c1 == 0) {
        $wc = ' cena <= ' . $c2;
    } else if ($c1 > 0 && $c2 > 0) {
        $wc = ' cena >= ' . $c1 . ' AND cena <= ' . $c2;
    } else {
        $wc = '';
    }
    //sprawdzić kombinacje dla ANDów
    if ($wt != '' && $wc != '') {
        $a1 = ' AND ';
    } else {
        $a1 = '';
    }
    if ($wt != '' && $wtk != '') {
        $a1 = ' AND ';
    } else {
        $a1 = '';
    }
    if ($wtk != '' && $wc != '') {
        $a2 = ' AND ';
    } else {
        $a2 = '';
    }
    if (($wt != '' || $wtk != '' || $wc != '')) {
        $wh = ' WHERE ';
    } else {
        $wh = '';
    }
    //echo 'SELECT * FROM produkty ' . $wh . $wt . $a1 . $wtk . $a2 . $wc . $ws;
    $result = mysqli_query($pol, 'SELECT * FROM produkty ' . $wh . $wt . $a1 . $wtk . $a2 . $wc . $ws);
    if (mysqli_num_rows($result) > 0) {
        while ($produkt = mysqli_fetch_object($result)) {
            /* $szer = '<script > window.screen.availWidth; </script>';
              $ile = ($szer * 0.84) / 230; */
            /* if ($produkt->id % 6 == 0) {
              echo '<br><br>';
              } */

            echo '<div class="produkt" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx=' . $produkt->id . '\';" >';
            echo '<div class="minimg" >';
            $rez = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $produkt->id . ' ORDER BY id ASC LIMIT 1');
            if (mysqli_num_rows($rez) > 0) {
                while ($z = mysqli_fetch_object($rez)) {
                    echo '<img alt="z1" src="panelCMS/pimg/' . $z->nazwa . ' " />';
                }
            } else {
                echo '<img alt="z1" src="ikon/prod.png" />';
            }
            echo '</div>';
            echo '<div class="etykp"><a style="font-size: 120%;" href="produkt.php?idx=' . ($produkt->id) . '" >' . ($produkt->nazwa) . '</a><br/><br/>';
            if ($produkt->dostep < 1)
            {
                echo '<span style="font-style: italic; color: #ff0000; font-weight: 600;">Dostępność: ' . $produkt->dostep . ' szt</span><br />';
            }
            elseif ($produkt->dostep < 4)
            {
                echo '<span style="font-style: italic; color: #ff8900; font-weight: 600;">Dostępność: ' . $produkt->dostep . ' szt</span><br />';
            }
            else
            {
                echo '<span style="font-style: italic; font-weight: 600;">Dostępność: ' . $produkt->dostep . ' szt</span><br />';
            }
            if ($produkt->scena == NULL) {
                echo '<span class="cena">' . number_format($produkt->cena, 2, ',', '.') . ' zł</span><br/>';
            } else {
                echo '<span class="scena">' . number_format($produkt->scena, 2, ',', '.') . ' zł</span><br/>';
                echo '<span class="acena">' . number_format($produkt->cena, 2, ',', '.') . ' zł</span><br/>';
            }

            echo '</div>';
            if (snm($produkt->id))
            {
                echo '<span><a class="dk" title="Naciśnij, aby dodać do koszyka" href="koszyk.php?id=' . ($produkt->id) . '">Do koszyka</a></span>';
            }
            else
            {
                echo '<span class="bp" title="Brak produktu na magazynie" >Brak produktu</span>';
            }
            echo '</div>';
        }
    } else {
        echo '<div class="error"><label>Brak produktów o podanych parametrach. Proszę zmienić ustawienia filtrowania.</label></div>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" >
    <title>.: JOLA - Butik internetowy - Produkty :.</title>
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
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <h1>Asortyment: </h1>
    <div id="zawartosc">
        <form method="post">
            <div style="display: inline-table; width: 100%; padding: 12px 16px; margin: 6px 18px 16px 4px; background-color: #fff;">
                <div id="fmobile">
                    <div class="option-heading" onclick="ps(this,children);">
                        <b>Filtruj:</b>
                        <div class="arrow-up">&#9650;</div>
                        <div class="arrow-down">&#9660;</div>
                    </div>
                    <div class="option-content">
                    <div class="option-heading" onclick="ps(this,children);">Typ:
                        <div class="arrow-up">&#9650;</div>
                        <div class="arrow-down">&#9660;</div>
                    </div>
                    <div class="option-content">
                        <div action="" method="post">
                            <div class="select">
                                <select name="t">
                                    <option value=" " <?php
                                    if (isset($_POST['t'])) {
                                        if ($_POST['t'] == " ") {
                                            echo 'selected';
                                        }
                                    }
                                    ?>>Wybierz
                                    </option>
                                    <option value="buty" <?php
                                    if (isset($_POST['t'])) {
                                        if ($_POST['t'] == "buty") {
                                            echo 'selected';
                                        }
                                    }
                                    ?>> Buty
                                    </option>
                                    <option value="torebki"
                                        <?php
                                    if (isset($_POST['t'])) {
                                        if ($_POST['t'] == "torebki") {
                                            echo 'selected';
                                        }
                                    }
                                    ?>> Torebki
                                    </option>
                                    <option value="dodatki" <?php
                                    if (isset($_POST['t'])) {
                                        if ($_POST['t'] == "dodatki") {
                                            echo 'selected';
                                        }
                                    }
                                    ?>> Dodatki
                                    </option>
                                </select>
                                <div class="select__arrow"></div>
                            </div>
                        </div>
                    </div>
                    <div class="option-heading" onclick="ps(this,children);">Materiał:
                        <div class="arrow-up">&#9650;</div>
                        <div class="arrow-down">&#9660;</div>
                    </div>
                    <div class="option-content">
                        <div class="select">
                            <select name="tk">
                                <option value=" " <?php
                                if (isset($_POST['tk'])) {
                                    if ($_POST['tk'] == " ") {
                                        echo 'selected';
                                    }
                                }
                                ?>>Wybierz
                                </option>
                                <option value="tkanina" <?php
                                if (isset($_POST['tk'])) {
                                    if ($_POST['tk'] == "tkanina") {
                                        echo 'selected';
                                    }
                                }
                                ?>> Tkanina
                                </option>
                                <option value="skora" <?php
                                if (isset($_POST['tk'])) {
                                    if ($_POST['tk'] == "skora") {
                                        echo 'selected';
                                    }
                                }
                                ?>> Skóra
                                </option>
                                <option value="inny" <?php
                                if (isset($_POST['tk'])) {
                                    if ($_POST['tk'] == "inny") {
                                        echo 'selected';
                                    }
                                }
                                ?>> Inny
                                </option>
                            </select>
                            <div class="select__arrow"></div>
                        </div>
                        </div>
                        <div class="option-heading" onclick="ps(this,children);">Przedział cenowy:
                            <div class="arrow-up">&#9650;</div>
                            <div class="arrow-down">&#9660;</div>
                        </div>
                        <div class="option-content">
                        <input class="tb" type="text" name="c1" placeholder="Minimalna kwota" value=<?php
                        if (isset($_POST['c1'])) {
                            if ($_POST['c1'] > 0){
                                echo $_POST['c1'];
                            }
                        }
                        ?> /> DO
                        <input class="tb" type="text" name="c2" placeholder="Maksymalna kwota" value=<?php
                        if (isset($_POST['c2']) > 0) {
                            if ($_POST['c2'] > 0) {
                                echo $_POST['c2'];
                            }
                        }
                        ?> />
                        </div>
                    </div>
                </div>
                <div id="fkomputer">
                    <b><h3>Filtry:</h3></b>
                    <h4>Rodzaj produktu: </h4>
                    <div class="select">
                        <select name="t">
                            <option value=" " <?php
                            if (isset($_POST['t'])) {
                                if ($_POST['t'] == " ") {
                                    echo 'selected';
                                }
                            }
                            ?>>Wybierz
                            </option>
                            <?php
                            $op = mysqli_query($pol, "SELECT DISTINCT typ FROM `produkty` ORDER BY typ ASC ");
                            while ($oop = mysqli_fetch_object($op))
                            {
                                echo '<option value="'.$oop->typ.'"';
                                if (isset($_POST['t']))
                                {
                                    if ($_POST['t'] == $oop->typ) {
                                        echo "selected > ";
                                    } else {
                                        echo " > ";
                                    }
                                }
                                echo $oop->typ.'</option>';
                            }
                            ?>
                        </select>
                        <div class="select__arrow"></div>
                    </div>
                    <h4>Materiał: </h4>
                    <div class="select">
                        <select name="tk" title="">
                            <option value=" " <?php
                            if (isset($_POST['tk'])) {
                                if ($_POST['tk'] == " ") {
                                    echo 'selected';
                                }
                            }
                            ?>>Wybierz
                            </option>
                            <?php
                            $om = mysqli_query($pol, "SELECT DISTINCT material FROM `produkty` ORDER BY material ASC");
                            while ($oom = mysqli_fetch_object($om))
                            {
                                echo '<option value="'.$oom->material.'"';
                                if (isset($_POST['tk']))
                                {
                                    if ($_POST['tk'] == $oom->material) {
                                        echo "selected > ";
                                    } else {
                                        echo " > ";
                                    }
                                }
                                echo $oom->material.'</option>';
                            }
                            ?>
                        </select>
                        <div class="select__arrow"></div>
                    </div>
                    <h4>Przedział cenowy: </h4>
                    <input class="tb" type="text" name="c1" placeholder="Minimalna kwota" <?php
                    if (isset($_POST['c1'])) {
                        if ($_POST['c1'] > 0)
                        {
                            echo ' value='.$_POST['c1'];
                        }
                    }
                    ?> /> DO
                    <input class="tb" type="text" name="c2" placeholder="Maksymalna kwota" <?php
                    if (isset($_POST['c2'])) {
                        if ($_POST['c2'] > 0) {
                            echo ' value=' . $_POST['c2'];
                        }
                    }
                    ?> />
                </div>
                <input type="submit" class="btn doprawej" value="Filtruj"/>
                <?php
                if (isset($_POST['t']) || isset($_POST['tk']) || isset($_POST['c1']) || isset($_POST['c2'])) {
                    if ($_POST['t'] != " " || $_POST['tk'] != " " || $_POST['c1'] > 0 || $_POST['c2'] > 0) {
                        echo '<input type="submit" class="btn doprawej" name="czyscf" value="Wyczyść filtry" /><br/>';
                    }
                }
                ?>
            </div>
            <div class="produkty">
                <div class="sortuj">
                <b>Sortuj: </b>
                <?php
                if (isset($_POST['s'])) {
                    if ($_POST['s'] == "A") {
                        echo '<label ><input type="radio" name="s" value="A" checked="checked"/> A - Z &nbsp;</label>';
                        $p = "A";
                    } else {
                        echo '<label><input type="radio" name="s" value="A" /> A - Z &nbsp;</label>';
                    }
                    if ($_POST['s'] == "Z") {
                        echo '<label><input type="radio" name="s" value="Z" checked />  Z - A &nbsp;</label>';
                        $p = "Z";
                    } else {
                        echo '<label><input type="radio" name="s" value="Z" />  Z - A &nbsp;</label>';
                    }
                    if ($_POST['s'] == "R") {
                        echo '<label><input type="radio" name="s" value="R" checked /> Cena rosnąco &nbsp;</label>';
                        $p = "R";
                    } else {
                        echo '<label><input type="radio" name="s" value="R" /> Cena rosnąco &nbsp;</label>';
                    }
                    if ($_POST['s'] == "M") {
                        echo '<label><input type="radio" name="s" value="M" checked /> Cena malejąco &nbsp;</label>';
                        $p = "M";
                    } else {
                        echo '<label><input type="radio" name="s" value="M" /> Cena malejąco &nbsp;</label>';
                    }
                    if ($_POST['s'] == "P") {
                        echo '<label><input type="radio" name="s" value="P" checked /> Popularne &nbsp;</label>';
                        $p = "P";
                    } else {
                        echo '<label><input type="radio" name="s" value="P" /> Popularne &nbsp;</label>';
                    }
                } else {
                    echo '<label><input type="radio" name="s" value="A" checked /> A - Z &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="Z" />  Z - A &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="R" /> Cena rosnąco &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="M" /> Cena malejąco &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="P" /> Popularne &nbsp;</label>';
                }
                ?>
                <input type="submit" class="btn" name="submit" value="Sortuj"><br/>
    </div>
                <div class="wbelka">
                    <?php prod($_POST['t'], $_POST['tk'], str_replace(',', '.', $_POST['c1']), str_replace(",", ".", $_POST['c2']), $_POST['s']); ?>
                </div>
            </div>
        </form>
    </div>
    <br/>
</div>
<?php include 'stopka.php'; ?>
<button onclick="topFunction();" id="myBtn" title="Na górę strony"><img src="ikon/aup.png"></button>
<div id="prv-billboard"></div>
</body>
</html>