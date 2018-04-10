<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 22.04.2017
 * Time: 23:43
 */

if (isset($_POST['rmaa']))
{
    header("Location: admin.php?karta=6");
}

if (!isset($_SESSION['login_a']))
{
    header("Location: index.php");
}

if (isset($_POST['rmaz']))
{
    $nrrma = $_GET['idrma'];
    $status = $_POST['status'];
    $uwagi = $_POST['uwagirma'];
    mysqli_query($pol, "UPDATE rma SET status =".$status." WHERE id=".$nrrma);
    mysqli_query($pol, "UPDATE rma SET uwagi ='".$uwagi."' WHERE id=".$nrrma);
    header("Location: admin.php?karta=6");
}

if (isset($_GET['idrma']))
{
    $erma = mysqli_query($pol, "SELECT * FROM rma WHERE id=" . $_GET['idrma'] . " ");
    while ($rrma = mysqli_fetch_object($erma)) {
        $o1 = NULL;
        $o2 = NULL;
        $o3 = NULL;
        $o4 = NULL;
        $o5 = NULL;
        $o6 = NULL;
        switch ($rrma->status) {
            case 'złożono': $o1 = "selected";
                break;
            case 'przyjęto': $o2 = "selected";
                break;
            case 'rozpatrzono': $o3 = "selected";
                break;
            case 'odrzucono': $o4 = "selected";
                break;
            case 'zakończono': $o5 = "selected";
                break;
        }
        echo 'Edycja zgłoszenia RMA nr ' . $_GET['idrma'];
        echo '<div>
    <form action="" method="post">
        <div>Status: <select class="select" name="status">
                <option value="1" ' . $o1 . '>złożono</option>
                <option value="2" ' . $o2 . '>przyjęto</option>
                <option value="3" ' . $o3 . '>rozpatrzono</option>
                <option value="4" ' . $o4 . '>odrzucono</option>
                <option value="5" ' . $o5 . '>zakończone</option>
            </select>
        </div>
        <br/>' . //Koszyk zamówienia:<br/><textarea name="tkoszyk">'. tabtostr(unserialize($rz->koszyk)).'</textarea><br/>'.
            'ID Klienta: <label><a href="klientinfo.php?idk=' . $rrma->id_uzyt . '" onclick="window.open(\'klientinfo.php?idk=' . $rrma->id_uzyt . '\', \'newwindow\', \'width=800, height=450\'); return false;">' . $rrma->id_uzyt . '</a></label><br/><br/>';
            $prodrma = mysqli_fetch_object(mysqli_query($pol,"SELECT * FROM produkty WHERE id=".$rrma->id_prod." "));
            echo '<label>Reklamowany produkt: '.$prodrma->nazwa.'</label><br/><br/>'.
                '<label>Opis: <br/>'.$rrma->opis.'</label><br/><br/>'.
            '<label>Uwagi: </label><br/><textarea class="tb" name="uwagirma">' . $rrma->uwagi . '</textarea><br/>
        <br/><input class="btn" type="submit" name="rmaz" value="Zapisz"><input class="btn" type="submit" name="rmaa" value="Anuluj"><br/>
    </form>
</div>';
    }
}
$qr = '';
if (isset($_SESSION['login_a']) && !isset($_GET['idz']) && !isset($_GET['idrma'])) {
    $sm0 = "";
    $sm1 = "";
    $sm2 = "";
    $sm3 = "";
    $sm4 = "";
    $sm5 = "";
    $sm6 = "";
    $sm7 = "";
    $sm8 = "";
    $sm9 = "";
    $sm10 = "";
    $sm11 = "";
    $sm12 = "";
    $sr0 = "";
    $sr1 = "";
    $sr2 = "";
    $sr3 = "";
    $today = getdate();
    if (!isset($_POST['mies']) && (!isset($_POST['nrk']) || !isset($_POST['nrz']) || !isset($_GET['nrz']))) {
        $_POST['mies'] = $today['mon'];
    }
    if (!isset($_POST['rok']) && (!isset($_POST['nrk']) || !isset($_POST['nrz']) || !isset($_GET['nrz']))) {
        $_POST['rok'] = $today['year'];
    }

    if (isset($_POST['rok']) && isset($_POST['mies'])) {
        $qr = 'WHERE MONTH(data)=' . $_POST['mies'] . ' AND YEAR(data)=' . $_POST['rok'];
    }
    if (isset($_POST['mies'])) {
        switch ($_POST['mies']) {
            case 0:
                $sm0 = "selected";
                break;
            case 1:
                $sm1 = "selected";
                break;
            case 2:
                $sm2 = "selected";
                break;
            case 3:
                $sm3 = "selected";
                break;
            case 4:
                $sm4 = "selected";
                break;
            case 5:
                $sm5 = "selected";
                break;
            case 6:
                $sm6 = "selected";
                break;
            case 7:
                $sm7 = "selected";
                break;
            case 8:
                $sm8 = "selected";
                break;
            case 9:
                $sm9 = "selected";
                break;
            case 10:
                $sm10 = "selected";
                break;
            case 11:
                $sm11 = "selected";
                break;
            case 12:
                $sm12 = "selected";
                break;
            default:
                ;
                break;
        }
    }
    if (isset($_POST['rok'])) {
        switch ($_POST['rok']) {
            case 0:
                $sr0 = "selected";
                break;
            case 2017:
                $sr1 = "selected";
                break;
            case 2018:
                $sr2 = "selected";
                break;
            case 2019:
                $sr3 = "selected";
                break;
        }
    }

    if (isset($_POST['nrk'])) {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_POST['nrk'] != '') {
            $qr = 'WHERE id_uzyt =' . $_POST['nrk'];
        }
        unset($_POST['nrk']);
    }

    if (isset($_POST['nrr'])) {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_POST['nrr'] != '') {
            $qr = 'WHERE id=' . $_POST['nrr'];
        }
        unset($_POST['nrr']);
    }
    if (isset($_GET['nrr'])) {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_GET['nrr'] != '') {
            $qr = 'WHERE id=' . $_GET['nrr'];
        }
        echo '<form action="" method="post"><input class="btn" type="submit" name="rsf" value="Usuń filtry" /></form>';

    }

    if (isset($_POST['rsf'])) {
        unset($_POST['nrk']);
        unset($_POST['nrr']);
        unset($_POST['rsf']);
        header("Location: admin.php?karta=6");
    }
    echo '<label>Fitruj reklamacje:</label><form action="" style="position: relative;" method="post">
    <label>Okres: </label>
    <select name="mies">
        <option value="" ' . $sm0 . ' >Miesiąc</option>
        <option value="1" ' . $sm1 . ' >Styczeń</option>
        <option value="2" ' . $sm2 . ' >Luty</option>
        <option value="3" ' . $sm3 . ' >Marzec</option>
        <option value="4" ' . $sm4 . ' >Kwiecień</option>
        <option value="5" ' . $sm5 . ' >Maj</option>
        <option value="6" ' . $sm6 . ' >Czerwiec</option>
        <option value="7" ' . $sm7 . ' >Lipiec</option>
        <option value="8" ' . $sm8 . ' >Sierpień</option>
        <option value="9" ' . $sm9 . ' >Wrzesień</option>
        <option value="10" ' . $sm10 . ' >Październik</option>
        <option value="11" ' . $sm11 . ' >Listopad</option>
        <option value="12" ' . $sm12 . ' >Grudzień</option>
    </select>
    <select name="rok">
        <option value="" ' . $sr0 . ' >Rok</option>
        <option value="2017" ' . $sr1 . ' >2017</option>
        <option value="2018" ' . $sr2 . ' >2018</option>
            <option value="2019" ' . $sr3 . ' >2019</option>
    </select>
    <label>lub Numer klienta: </label><input type="text" class="tb" name="nrk" />
    <label>lub Numer reklamacji: </label><input type="text" class="tb" name="nrr" />
    <input class="btn" type="submit" name="fpdata" value="Pokaż"  /></form><br/>';
    echo '<table class="tbl" border="2" cellspacing="4" cellpadding="5"><thead><tr>
                                    <th> Numer </th>
                                    <th> Data złożenia </th>
                                    <th> Status </th>
                                    <th> Produkt </th>
                                    <th> Nr zamówienia </th>
                                    <th> Opis </th>
                                    <th> Uwagi </th>
                                    <th> Klient </th>
                                    <th> Opcje </th>
                                </tr></thead><tbody>';
    $rma = mysqli_query($pol, "SELECT * FROM rma " . $qr);
    if (mysqli_num_rows($rma) > 0) {
        while ($lrma = mysqli_fetch_object($rma)) {
            $pr = mysqli_query($pol, 'SELECT * FROM produkty WHERE id=' . $lrma->id_prod . '');
            $produkt = mysqli_fetch_object($pr);
            echo '<tr>';
            echo '<td><label>' . $lrma->id . '</label></td>';
            $datarma = date_create($lrma->data);
            echo '<td>' . str_replace(" ","<br/>", date_format($datarma,"d.m.Y H:i")) . '</td>';
            echo '<td>' . $lrma->status . '</td>';
            echo '<td>' . $produkt->nazwa . ' (ID: ' . $produkt->id . ')</td>';
            echo '<td>' . $lrma->id_zam . '</td>';
            echo '<td>' . $lrma->opis . '</td>';
            echo '<td>' . $lrma->uwagi . '</td>';
            echo '<td><label><a href="klientinfo.php?idk=' . $lrma->id_uzyt . '" onclick="window.open(\'klientinfo.php?idk=' . $lrma->id_uzyt . '\', \'newwindow\', \'width=800, height=450\'); return false;">' . $lrma->id_uzyt . '</a></label></td>';
            echo '<td><form id="opdz" method="post" action="admin.php?karta=6&idrma=' . $lrma->id . '"><input type="submit" class="btn" value="Edytuj" name="edytujrma" /></form></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="9">Brak zgłoszeń</td></tr>';
    }

    echo '</tbody></table>';
}

?>