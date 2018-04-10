<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();

$_SESSION['astr'] = "szukaj";
require 'polacz.php';

$szp = 'Nie podano frazy.';
if (isset($_GET['sz'])) {
    $szp = $_GET['sz'];
}

function szukaj() { //($t, $tk, $c1, $c2, $s) {
    require 'polacz.php';
    if (isset($_GET['sz']))
    {
        $szp = $_GET['sz'];
        $result = mysqli_query($pol, 'SELECT * FROM produkty WHERE nazwa="' . $szp . '" OR typ="' . $szp . '" OR id="' . $szp.'"');
        if (mysqli_num_rows($result) > 0) {
            while ($produkt = mysqli_fetch_object($result)) {
                echo '<div class="produkt" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx=' . $produkt->id . '\';" >';
                echo '<div class="minimg" >';
                $rez = mysqli_query($pol, 'SELECT * FROM zdj WHERE id_prod=' . $produkt->id . ' ORDER BY id ASC');
                if ($rez) {
                    while ($z = mysqli_fetch_object($rez)) {
                        echo '<img alt="z1" width="72" height="72" src="data:image;base64,' . $z->nazwa . ' "  >';
                        break;
                    }
                } else {
                    echo '<img alt="z1" width="72" height="72" src="prod.png">';
                }
                echo '</div>';
                echo '<div class="etykp"><a href="produkt.php?idx=' . ($produkt->id) . '" >' . ($produkt->nazwa) . '</a><br />';
                echo 'Dostępność: ' . $produkt->dostep . ' szt<br />';
                if ($produkt->scena == NULL) {
                    echo '<span class="cena">' . number_format($produkt->cena, 2, ',', '.') . ' zł</span><br/>';
                } else {
                    echo '<span class="scena">' . number_format($produkt->scena, 2, ',', '.') . ' zł</span><br/>';
                    echo '<span class="acena">' . number_format($produkt->cena, 2, ',', '.') . ' zł</span><br/>';
                }
                echo '<span><a class="dk" href="koszyk.php?id=' . ($produkt->id) . '">Do koszyka</a></span>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="error"><label>Nie znaleziono produktów o podanej frazie. Proszę sprawdź szukaną frazę.</label></div><br/><br/><br/><br/><br/><br/>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Wyszukiwarka</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css" />
    <script type="text/javascript" src="skrypt.js" ></script>
    <style>
        #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 8%;
            border: none;
            outline: none;
            background-color: #00a6e8;
            color: white;
            font-weight: bolder;
            font-size: 36px;
            cursor: pointer;
            padding: 2px 15px;
            border-radius: 12px;
            z-index: 1;
        }

        #myBtn:hover {
            background-color: #0095e8;
        }
    </style>
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
    </script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php
    include 'menu.php';
    if (is_numeric($szp)) {
        echo '<h1>Wynik wyszukiwania dla identyfikatora produktu: '. $szp.'</h1>';
    }
    else
    {
        echo '<h1>Wynik wyszukiwania dla frazy: '. $szp.'</h1>';
    }
    ?>

    <div id="zawartosc">
        <div id="webelka">
            <div style="display: inline-table; width: 100%; padding: 11px 12px 8px 12px; margin: 6px 6px 16px 6px; background-color: #fff;">
                <form action="" method="post">
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
                                        <option value="torebki" <?php
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
                            if (isset($_POST['c1']) > 0) {
                                echo $_POST['c1'];
                            }
                            if (isset($_POST['c1']) == 0) {
                                echo "0.00";
                            }
                            ?> /> DO
                            <input class="tb" type="text" name="c2" placeholder="Maksymalna kwota" value=<?php
                            if (isset($_POST['c2']) > 0) {
                                echo $_POST['c2'];
                            }
                            if (isset($_POST['c2']) == 0) {
                                echo "0.00";
                            }
                            ?> />
                        </div>
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
        </div>
        <div class="produkty">
            <div style="padding: 11px 12px 8px 12px; margin: 6px 6px 16px 6px; background-color: #fff;">
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
                        echo '<label><input type="radio" name="s" value="Z" checked />  Z - A &nbsp</label>';
                        $p = "Z";
                    } else {
                        echo '<label><input type="radio" name="s" value="Z" />  Z - A &nbsp</label>';
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
                    echo '<label><input type="radio" name="s" value="Z" />  Z - A &nbsp</label>';
                    echo '<label><input type="radio" name="s" value="R" /> Cena rosnąco &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="M" /> Cena malejąco &nbsp;</label>';
                    echo '<label><input type="radio" name="s" value="P" /> Popularne &nbsp;</label>';
                }
                ?>
                <input type="submit" class="btn" name="submit" value="Sortuj"><br/>
                </form>
            </div>
            <?php szukaj(); //($_POST['t'], $_POST['tk'], $_POST['c1'], $_POST['c2'], $_POST['s']); ?>
        </div>

    </div>
    <br/>
    <button onclick="topFunction();" id="myBtn" title="Go to top"> &#8679 </button>
</div>
<?php include 'stopka.php'; ?>
<div id="prv-billboard"></div>
</body>
</html>