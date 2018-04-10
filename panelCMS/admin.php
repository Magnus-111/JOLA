<?php
session_start();
ob_start();
if (isset($_POST['btn_logout_a'])) {
    unset($_SESSION['login_a']);
    unset($_SESSION['haslo_a']);
    header("Location: ../panelCMS/index.php");
    exit();
}

if (isset($_POST['btn_gl'])) {
    unset($_GET['karta']);
}

if (!isset($_SESSION['login_a'])) {
    header("Location: index.php");
    exit();
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styla.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        function zt(ob, p) {
            if ((ob).children('.arrow-down').is(':hidden')) {
                $(ob).children('.arrow-up').hide();
                $(ob).children('.arrow-down').show();
            } else {
                $(ob).children('.arrow-up').show();
                $(ob).children('.arrow-up').css({display: 'inline-block'});
                $(ob).children('.arrow-down').hide();
            }
            $(ob).next(p).slideToggle(0);
        }

        function ps(ob, p) {
            if ($(ob).children('.arrow-down').is(':hidden')) {
                $(ob).children('.arrow-up').hide();
                $(ob).children('.arrow-down').show();
            } else {
                $(ob).children('.arrow-up').show();
                $(ob).children('.arrow-up').css({display: 'inline-block'});
                $(ob).children('.arrow-down').hide();
            }
            $(ob).next(p).slideToggle(700, "swing");
        }
    </script>
    <title>.: JOLA - Panel administracyjny :.</title>
</head>
<body>
<div id="prv-billboard"></div>
<div id="pma">
    <br/>
    <?php
    echo '<a href="../" target="_blank">Pokaż sklep</a><br/><br/>';
    echo 'Witaj ' . $_SESSION['login_a'] . '<br/><br/>';
    ?>
    <form method="post" action="">
        <input type="submit" class="btn" value="Wyloguj" name="btn_logout_a"/><br/><br/></form>
    <?php
    if (isset($_GET['karta'])) {
        //echo '<input type="submit" class="btn" value="Panel główny" name="btn_gl" />';
        echo '<a href="admin.php" target="_self">Panel główny</a><br/>';
    }
    ?>
    </form>
    <br/>
    <label> Zarządzanie: </label><br/>
    <?php
    if (isset($_GET['karta'])) {
        if ($_GET['karta'] != 0) echo '<a href="admin.php?karta=0" target="_self">Zamówieniami</a><br/>';
        if ($_GET['karta'] != 1) echo '<a href="admin.php?karta=1" target="_self">Produktami</a><br/>';
        if ($_GET['karta'] != 7) echo '<a href="admin.php?karta=7" target="_self">Wysyłką</a><br/>';
        if ($_GET['karta'] != 2) echo '<a href="admin.php?karta=2" target="_self">Kodami rabatowymi</a><br/>';
        if ($_GET['karta'] != 5) echo '<a href="admin.php?karta=5" target="_self">Promocjami</a><br/>';
        if ($_GET['karta'] != 6) echo '<a href="admin.php?karta=6" target="_self">RMA</a><br/>';
        if ($_GET['karta'] != 3) echo '<a href="admin.php?karta=3" target="_self">Kontami moderatorów</a><br/>';
        if ($_GET['karta'] != 4) echo '<a href="admin.php?karta=4" target="_self">Kontami klientów</a><br/>';
        if ($_GET['karta'] != 8) echo '<a href="admin.php?karta=8" target="_self"> Newsletter</a><br/>';
    } else {
        echo '<a href="admin.php?karta=0" target="_self">Zamówieniami</a><br/>
              <a href="admin.php?karta=1" target="_self">Produktami</a><br/>
              <a href="admin.php?karta=7" target="_self">Wysyłką</a><br/>
              <a href="admin.php?karta=2" target="_self">Kodami rabatowymi</a><br/>
              <a href="admin.php?karta=5" target="_self">Promocjami</a><br/>
              <a href="admin.php?karta=6" target="_self">RMA</a><br/>
              <a href="admin.php?karta=3" target="_self">Kontami moderatorów</a><br/>
              <a href="admin.php?karta=4" target="_self">Kontami klientów</a><br/>
              <a href="admin.php?karta=8" target="_self"> Newsletter</a><br/> ';
    }
    ?>

</div>
<div id="panela">
    <?php
    require '../polacz.php';
    require '../towar.php';
    if (isset($_GET['karta'])) {
        if ($_GET['karta'] == 0) {
            echo '<h2>Zamówienia</h2>';
            $today = getdate();
            if ($today['mon'] < 10) {
                $z0 = '0';
            } else {
                $z0 = '';
            }
            echo '<i><h3>Dziś jest: ' . $today['mday'] . '.' . $z0 . $today['mon'] . '.' . $today['year'] . '</h3></i>';
            include 'zamowienia.php';
        }
        if ($_GET['karta'] == 1) {
            echo '<h2>Produkty</h2>';
            if (!isset($_GET['idp'])) {
                include 'produktplus.php';
                echo '<br/><br/>';
                echo '<table class="tbl" border="1" cellspacing="2" cellpadding="3"><thead><tr><th>Numer produktu</th><th>Artykuł</th><th>Stan na magazynie</th><th>Cena za jednostkę</th><th></th></tr></thead><tbody>';
                $res = mysqli_query($pol, 'SELECT * FROM produkty');
                while ($produkt = mysqli_fetch_object($res)) {
                    echo '<tr><td>' . $produkt->id . '</td>';
                    echo '<td>' . $produkt->nazwa . '</td>';
                    echo '<td>' . $produkt->dostep . '</td>';
                    echo '<td>';
                    if ($produkt->scena != NULL) {
                        echo '<s>' . number_format($produkt->scena, 2, ',', ' ') . ' zł</s> ';
                        echo number_format($produkt->cena, 2, ',', ' ') . ' zł';
                    } else {
                        echo number_format($produkt->cena, 2, ',', ' ') . ' zł';
                    }
                    echo '</td>';
                    /* echo ''.$produkt->nazwa.'';
                      echo ''.$produkt->nazwa.''; */
                    echo '<td>';
                    echo '<form id="opdz" method="post" action="admin.php?karta=1&idp=' . $produkt->id . '"><input type="submit" class="btn" value="Edytuj" name="" /></form>'
                        . '<form id="opdz" method="post" action="admin.php?karta=1&idp=' . $produkt->id . '&idup=' . $produkt->id . '"><input type="submit" class="btn" value="Usuń" name="" /></form>';
                    echo '</td></tr>';
                }
                echo '</tbody></table><br/>';
            }
            else if (isset($_GET['idp']) && isset($_GET['idup'])) {

                include 'usunprodukt.php';
            }
            else if (isset($_GET['idp']) && !isset($_GET['idup'])) {

                include 'edytujprodukt.php';
            }

        }
        if ($_GET['karta'] == 2) {
            echo '<h2>Kody rabatowe</h2>';
            include 'kody.php';
        }
        if ($_GET['karta'] == 3) {
            echo '<h2>Konta moderatorów: </h2>';
            include "ekd.php";
        }
        if ($_GET['karta'] == 4) {
            echo '<h2>Konta klientów: </h2>';
            include "klienci.php";
        }
        if ($_GET['karta'] == 5) {
            echo '<h2>Promocje</h2>';
            include 'promocje.php';
        }
        if ($_GET['karta'] == 6) {
            echo '<h2>Reklamacje</h2>';
            include 'zrma.php';
        }
        if ($_GET['karta'] == 7)
        {
            echo '<h2>Wysyłka: </h2>';
            include 'wysylka.php';
        }
        if ($_GET['karta'] == 8)
        {
            echo '<h2>Newslettery</h2>';
        }
    } else {
    echo '<h2>Nowe zamówienia:</h2>';
    $oz = mysqli_query($pol, "SELECT * FROM lz WHERE status = 'złożone' ORDER BY id DESC LIMIT 7");
    echo '<table class="tbl" border="2" cellspacing="4" cellpadding="5">
                            <thead>
                                <tr>
                                    <th> Numer </th>
                                    <th> Data złożenia </th>
                                    <th> Status </th>
                                    <th> Koszyk </th>
                                    <th> Płatność </th>
                                    <th> Uwagi </th>
                                    <th> Klient </th>
                                </tr>
                            </thead>
                            <tbody>';
    if (mysqli_num_rows($oz) > 0) {
        while ($zz = mysqli_fetch_object($oz)) {
            $sumz = 0.00;
            echo '<tr>';
            echo '<td><a href="admin.php?karta=0&nrz='.$zz->id.'">' . $zz->id . '</a></td>';
            echo '<td><label>' . str_replace(" ", "<br/>", $zz->data) . '</label></td>';
            echo '<td><label>' . $zz->status . '</label></td>';
            echo '<td>';
            echo '<div class = "option-heading" onclick = "ps(this, children);">Koszyk zamówienia<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div>';
            echo '<div id = "" class = "option-content">';
            echo '<table class="tbl" border="2" cellspacing="3" cellpadding="5"><thead><tr><th> Produkt </th><th> Cena </th><th> Sztuk </th><th> Całość </th></tr></thead><tbody>';
            $kz = unserialize($zz->koszyk);
            for ($i = 0; $i < count($kz); $i++) {
                echo '<tr>';
                echo '<td><label>' . ($kz[$i]->nazwa) . '</label></td>';
                echo '<td><label>' . number_format($kz[$i]->cena, 2, ',', ' ') . ' zł</label></td>';
                echo '<td><label>' . ($kz[$i]->sztuk) . '</td>';
                echo '<td><label>' . number_format($kz[$i]->cena * $kz[$i]->sztuk, 2, ',', ' ') . ' zł</label></td>';
                $sumz += $kz[$i]->cena * $kz[$i]->sztuk;
                echo '</tr>';
            }
            echo '<tr><td colspan="3"><b>Suma zamówienia: </b></td><td><b>' . number_format($sumz, 2, ',', ' ') . ' zł</b></td></tr>';
            echo '</tbody></table></div>';
            echo '</td>';
            echo '<td><label>' . $zz->platnosc . '</label></td>';
            $uw = "Brak uwag";
            if (!empty($zz->uwagi)) {
                $uw = $zz->uwagi;
            }
            echo '<td style=""><div class = "option-heading" onclick = "ps(this, children);">Rejestr uwag<div class = "arrow-up">&#9650;</div> <div class="arrow-down">&#9660;</div></div><div id = "" class = "option-content"><label>' . $uw . '</label></div></td>';
            $klient = $zz->id_uzyt;
            echo '<td><label><a href="klientinfo.php?idk=' . $klient . '" onclick="window.open(\'klientinfo.php?idk=' . $klient . '\', \'newwindow\', \'width=800, height=450\'); return false;">' . $klient . '</a></label></td>';
            echo '</tr>';
        }
    }
    echo '</tbody></table><br/>';
    echo '<h2>Nowe reklamacje:</h2>';
    echo '<table class="tbl" border="2" cellspacing="4" cellpadding="5"><thead><tr>
                                    <th> Numer </th>
                                    <th> Data złożenia </th>
                                    <th> Status </th>
                                    <th> Produkt </th>
                                    <th> Nr zamówienia </th>
                                    <th> Opis </th>
                                    <th> Uwagi </th>
                                    <th> Klient </th>
                                </tr></thead><tbody>';
        $rma = mysqli_query($pol, "SELECT * FROM rma WHERE status = 'złożono' ORDER BY id DESC LIMIT 7");
        if (mysqli_num_rows($rma) > 0) {
            while ($lrma = mysqli_fetch_object($rma)) {
                $pr = mysqli_query($pol, 'SELECT * FROM produkty WHERE id='.$lrma->id_prod.'');
                $produkt = mysqli_fetch_object($pr);
                echo '<tr>';
                echo '<td><a href="admin.php?karta=6">'.$lrma->id.'</a></td>';
                echo '<td>'.$lrma->data.'</td>';
                echo '<td>'.$lrma->status.'</td>';
                echo '<td>'.$produkt->nazwa.' (ID: '.$produkt->id.')</td>';
                echo '<td>'.$lrma->id_zam.'</td>';
                echo '<td>'.$lrma->opis.'</td>';
                echo '<td>'.$lrma->uwagi.'</td>';
                echo '<td><label><a href="klientinfo.php?idk=' . $lrma->id_uzyt . '" onclick="window.open(\'klientinfo.php?idk=' . $lrma->id_uzyt . '\', \'newwindow\', \'width=800, height=450\'); return false;">' . $klient . '</a></label></td>';
                echo '</tr>';
            }
        }
        else
        {
            echo '<tr><td colspan="8">Brak zgłoszeń</td></tr>';
        }
    }
    echo '</tbody></table>';
    ?>
</div>
</body>
</html>