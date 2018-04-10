<?php
isset($_SESSION['ogzz']);
if (isset($_SESSION['ogzz']) == NULL) {
    $_SESSION['ogzz'] = 0;
}
require 'polacz.php';
//mysql_query('SET NAMES utf8');
$rezz = mysqli_query($pol, "SELECT * FROM lz WHERE id_uzyt=" . $_SESSION['idk'] . " AND status = 5 ORDER BY id DESC");
if (mysqli_num_rows($rezz) > 0) {
    $listaz = array();
    while ($lz = mysqli_fetch_object($rezz)) {
        $listaz[] += $lz->id;
    }
    if (isset($_POST['zp3'])) {
        if ($_SESSION['ogzz'] > 0) {
            $_SESSION['ogzz']--;
        }
        else
        {
            $_SESSION['ogzz'] = count($listaz) - 1;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lzz\').click(); </script>';
    }
    if (isset($_POST['zn3'])) {
        if ($_SESSION['ogzz'] < count($listaz) - 1) {
            $_SESSION['ogzz']++;
        }
        else
        {
            $_SESSION['ogzz'] = 0;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lzz\').click(); </script>';
    }
    ?>
    <form action="" method="post">
        <input type="submit" class="btn" style="font-style: normal; font-weight: bold;" name="zp3" value="❮" />
        <label><?php echo ($_SESSION['ogzz'] + 1) . " / " . count($listaz); ?> </label>
        <input type="submit" class="btn" style="font-style: normal; font-weight: bold;" name="zn3" value="❯" />
    </form>
    <br/>
    <?php
    $sz3 = 0;
    $rz3 = mysqli_query($pol, 'SELECT * FROM lz WHERE id_uzyt=' . $_SESSION['idk'] . ' AND id=' . $listaz[$_SESSION['ogzz']] . ' AND status = 5 ');
    while ($z = mysqli_fetch_object($rz3)) {
        $zk = unserialize($z->koszyk);
        echo '<label><b>Numer zamówienia:</b> ' . $z->id . '</label><br/><br/>';
        echo '<label><b>Data złożenia zamówienia:</b> ' . $z->data . '</label><br/><br/>';
        echo '<label><b>Metoda płatności:</b> ' . $z->platnosc . '</label><br/><br/>';
        echo '<label><b>Status zamówienia:</b> ' . $z->status . '</label><br/><br/>';
        echo '<table class="tabela" border="0" cellspacing="3" cellpadding="6"><thead><tr><th>&nbsp;Produkt&nbsp;</th><th>&nbsp;Cena / szt.&nbsp;</th><th>&nbsp;Sztuk&nbsp;</th><th>&nbsp;Całość&nbsp;</th>
                              </tr></thead><tbody>';
        for ($i = 0; $i < count($zk); $i++) {
            $sz3 += ($zk[$i]->cena * $zk[$i]->sztuk);
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
    echo '<td colspan="2"><b>SUMA: </b></td><td>|</td><td><label><b>'.number_format($sumaz, 2, ',', ' ').' zł</b></label></td>';
    echo '</tbody></table>';
}
else
{
    echo '<label><b>Brak zakończonych zamówień</b></label>';
}
?>