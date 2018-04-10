<?php
isset($_SESSION['ogzr']);
if (isset($_SESSION['ogzr']) == NULL) {
    $_SESSION['ogzr'] = 0;
}
require 'polacz.php';
$rezz = mysqli_query($pol, "SELECT * FROM lz WHERE id_uzyt=" . $_SESSION['idk'] . " AND status <> 5 AND status <> 6 ORDER BY id DESC ");
if (mysqli_num_rows($rezz) > 0) {
    $listaz = array();
    while ($lz = mysqli_fetch_object($rezz)) {
        $listaz[] += $lz->id;
    }
    if (isset($_POST['zp2'])) {
        if ($_SESSION['ogzr'] > 0) {
            $_SESSION['ogzr']--;
        } else {
            $_SESSION['ogzr'] = count($listaz) - 1;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lzr\').click(); </script>';
    }
    if (isset($_POST['zn2'])) {
        if ($_SESSION['ogzr'] < count($listaz) - 1) {
            $_SESSION['ogzr']++;
        } else {
            $_SESSION['ogzr'] = 0;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lzr\').click(); </script>';
    }
    echo '<form action="" method="post">
        <input type="submit" class="btn" style="font-style: normal; font-weight: bolder;" name="zp2" value="❮" />
        <label>'.($_SESSION['ogzr'] + 1). ' / ' . count($listaz).'</label>
        <input type="submit" class="btn" style="font-style: normal; font-weight: bolder;" name="zn2" value="❯" />
    </form><br/>';
    $sumaz = 0;
    $rezultz = mysqli_query($pol, 'SELECT * FROM lz WHERE id_uzyt=' . $_SESSION['idk'] . ' AND id=' . $listaz[$_SESSION['ogzr']] . ' AND status <> 5 AND status <> 6');
    while ($z = mysqli_fetch_object($rezultz)) {
        $zk = unserialize($z->koszyk);
        echo '<label><b>Numer zamówienia:</b> ' . $z->id . '</label><br/><br/>';
        echo '<label><b>Data złożenia zamówienia:</b> ' . $z->data . '</label><br/><br/>';
        echo '<label><b>Metoda płatności:</b> ' . $z->platnosc . '</label><br/><br/>';
        echo '<label><b>Status zamowienia:</b> ' . $z->status . '</label><img style="cursor: help;" width="14" height="14" src="ikon/pomoc.png" title="Statusy zamówień: &#10;- złożone - twoje zamówienie czeka na zatwierdzenie do realizacji, &#10;- opłacone - zamówienie zostało opłacone, &#10;- zatwierdzone - zamówienie jest przygotowywane, &#10;- wysłane - zamówienie zostało wysłane na wskazany adres, w przypadku odbioru osobistego zamówienie czeka w skazanym punkcie odbioru,&#10;" />';
        if (($z->status != "zapłacone" && $z->status != "wysłane") && $z->platnosc != "gotówka") {
            echo '<input type=button class="btn" name="" value="opłać" />';
        }
        echo '<br/><br/>';
        echo '<table class="tabela" border="0" cellspacing="3" cellpadding="6"><thead><tr><th>&nbsp;Produkt&nbsp;</th><th>&nbsp;Cena / szt.&nbsp;</th><th>&nbsp;Sztuk&nbsp;</th><th>&nbsp;Całość&nbsp;</th>
                              </tr></thead><tbody>';
        for ($i = 0; $i < count($zk); $i++) {
            $sumaz += ($zk[$i]->cena * $zk[$i]->sztuk);
            echo '<tr>';
            echo '<td><label>' . ($zk[$i]->nazwa) . '</label></td>';
            echo '<td><label>' . number_format($zk[$i]->cena, 2, ',', ' ') . ' zł</label></td>';
            echo '<td><label>' . ($zk[$i]->sztuk) . '</td>';
            echo '<td><label>' . number_format($zk[$i]->cena * $zk[$i]->sztuk, 2, ',', ' ') . ' zł</label></td>';
            echo '</tr>';
        }
        echo '<label><b>Uwagi:</b> ' . $z->uwagi . '</label><br/><br/>';
        break;
    }
    echo '<td colspan="2"><b>SUMA: </b></td><td>|</td><td><label><b>' . number_format($sumaz, 2, ',', ' ') . ' zł</b></label></td>';
    echo '</tbody></table>';
} else {
    echo '<label><b>Brak realizowanych zamówień</b></label>';
}
?>