<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();
if (isset($_GET['idk']))
{
    $_SESSION['idk'] = $_GET['idk'];
}

isset($_SESSION['ogza']);
if (isset($_SESSION['ogza']) == NULL) {
    $_SESSION['ogza'] = 0;
}
require 'polacz.php';
$rezz = mysqli_query($pol, "SELECT * FROM lz WHERE id_uzyt=" . $_SESSION['idk'] . " AND status = 6 ORDER BY id DESC");
if (mysqli_num_rows($rezz) > 0) {

    $listaz = array();
    while ($lz = mysqli_fetch_object($rezz)) {
        $listaz[] += $lz->id;
    }
    if (isset($_POST['zp4'])) {
        if ($_SESSION['ogza'] > 0) {
            $_SESSION['ogza']--;
        }
        else
        {
            $_SESSION['ogza'] = count($listaz) - 1;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lza\').click(); </script>';
    }
    if (isset($_POST['zn4'])) {
        if ($_SESSION['ogza'] < count($listaz) - 1) {
            $_SESSION['ogza']++;
        }
        else
        {
            $_SESSION['ogza'] = 0;
        }
        echo '<script type="text/javascript" > document.getElementById(\'lza\').click(); </script>';
    }
    ?>
    <form action="" method="post">
        <input type="submit" class="btn" style="font-style: normal; font-weight: bold;" name="zp4" value="❮" />
        <label><?php echo ($_SESSION['ogza'] + 1) . " / " . count($listaz); ?> </label>
        <input type="submit" class="btn" style="font-style: normal; font-weight: bold;" name="zn4" value="❯" />
    </form>
    <br/>
    <?php
    $sumaz = 0;
    $rezultz = mysqli_query($pol, 'SELECT * FROM lz WHERE id_uzyt=' . $_SESSION['idk'] . ' AND id=' . $listaz[$_SESSION['ogza']] . ' AND status = 6');
    while ($z = mysqli_fetch_object($rezultz)) {
        $zk = unserialize($z->koszyk);
        echo '<label><b>Numer zamówienia:</b> ' . $z->id . '</label><br/><br/>';
        echo '<label><b>Data złożenia zamówienia:</b> ' . $z->data . '</label><br/><br/>';
        echo '<label><b>Metoda płatności:</b> ' . $z->platnosc . '</label><br/><br/>';
        echo '<label><b>Status zamówienia:</b> ' . $z->status . '</label><br/><br/>';
        echo '<table class="tabela" border="0" cellspacing="3" cellpadding="6">
            <thead><tr><th>&nbsp;Produkt&nbsp;</th><th>&nbsp;Cena / szt.&nbsp;</th><th>&nbsp;Sztuk&nbsp;</th><th>&nbsp;Całość&nbsp;</th></tr></thead><tbody>';
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
    echo '<td colspan="2"><b>SUMA: </b></td><td>|</td><td><label><b>'.number_format($sumaz, 2, ',', ' ').' zł</b></label></td>';
    echo '</tbody></table>';
} else {
    echo '<label><b>Brak anulowanych zamówień</b></label>';
}
?>