<?php

if (!isset($_SESSION['login_a']))
{
    header("Location: index.php");
}

if (isset($_POST['zez'])) {
    $status = $_POST['status'];
    $platnosc = $_POST['platnosc'];
    $klient = $_POST['idklienta'];
    $uwagi = $_POST['uwagi'];
    $koszyk = unserialize(serialize($koszykz));
    mysqli_query($pol, "UPDATE `lz` SET `id_uzyt` =" . $klient . " WHERE `lz`.`id` =" . $_GET['idz']);
    mysqli_query($pol, "UPDATE `lz` SET `status` =" . $status . " WHERE `lz`.`id` =" . $_GET['idz']);
    mysqli_query($pol, "UPDATE `lz` SET `platnosc` =" . $platnosc . " WHERE `lz`.`id` =" . $_GET['idz']);
    mysqli_query($pol, "UPDATE `lz` SET `uwagi` = \"" . $uwagi . "\" WHERE `lz`.`id` =" . $_GET['idz']);
    header("Location: admin.php?karta=0");
}

if (isset($_GET['idz']) && isset($_SESSION['login_a'])) {
    $ez = mysqli_query($pol, "SELECT * FROM `lz` WHERE id=" . $_GET['idz'] . "");
    while ($rz = mysqli_fetch_object($ez)) {
        $_SESSION['koszykz'] = unserialize($rz->koszyk);
        $o1 = NULL;
        $o2 = NULL;
        $o3 = NULL;
        $o4 = NULL;
        $o5 = NULL;
        $o6 = NULL;
        $o7 = NULL;
        $o8 = NULL;
        $o9 = NULL;
        switch ($rz->status) {
            case 'złożone': $o1 = "selected";
                break;
            case 'zatwierdzone': $o2 = "selected";
                break;
            case 'zapłacone': $o3 = "selected";
                break;
            case 'wysłane': $o4 = "selected";
                break;
            case 'zakończone': $o5 = "selected";
                break;
            case 'anulowane': $o6 = "selected";
                break;
        }
        switch ($rz->platnosc) {
            case 'gotówka': $o7 = "selected";
                break;
            case 'przelew': $o8 = "selected";
                break;
            case 'karta': $o9 = "selected";
                break;
        }
        echo '<big>Edycja zamówienia numer: ' . $_GET['idz'].'</big>';
        echo '<div><form action="" method="post">
            <div>Status: <select class="select" name="status">
                <option value="1" ' . $o1 . '>złożone</option>
                <option value="2" ' . $o2 . '>zatwierdzone</option>
                <option value="3" ' . $o3 . '>zapłacone</option>
                <option value="4" ' . $o4 . '>wysłane</option>
                <option value="5" ' . $o5 . '>zakończone</option>
                <option value="6" ' . $o6 . '>anulowane</option>  
                </select><br/><br/>
                Metoda płatności: <select name="platnosc">
                <option value="1" ' . $o7 . '>Gotówka</option>
                <option value="2" ' . $o8 . '>Przelew</option>
                <option value="3" ' . $o9 . '>Karta</option>
                </select>
            </div>
        <br/>';
        echo '<table class="tbl" border="0" cellspacing="4" cellpadding="8"><thead><tr><th>Produkt</th><th>Cena za szt.</th><th>Sztuk</th><th>Całość</th><th>Opcje</th></tr></thead>';
        echo '<tbody>';
        $koszykz = $_SESSION['koszykz'];
        $waga = 0.00;
        for ($i = 0; $i < count($koszykz); $i++)
        {
            echo '<tr>';
            echo '<td><label>'.$koszykz[$i]->nazwa.'</label></td>';
            echo '<td><label>'.str_replace('.',',', $koszykz[$i]->cena).' zł</label></td>';
            echo '<td><label>'.$koszykz[$i]->sztuk.'</label></td>';
            echo '<td><label>'.str_replace('.',',', $koszykz[$i]->cena * $koszykz[$i]->sztuk).' zł</label></td>';
            $waga += $koszykz[$i]->sztuk * $koszykz[$i]->waga;
            echo '<td>';
            echo '<a href="admin.php?karta=0&idz='.$_GET['idz'].'&usun='.$i.'" title="Usuń produkt z zamówienia"><img src="../ikon/usun.png" height="16" /></a> ';
            if (substr($koszykz[$i]->id,0,1) != "d" && substr($koszykz[$i]->id,0,1) != "p" && substr($koszykz[$i]->id,0,2) != "kr")
            {
                echo '<a class="btn" href="admin.php?karta=0&idz='.$_GET['idz'].'&dodaj='.$koszykz[$i]->id.'" title="Zwiększ liczbę sztuk produktu w zamówieniu.">+</a> ';
                echo '<a class="btn" href="admin.php?karta=0&idz='.$_GET['idz'].'&odejmij='.$koszykz[$i]->id.'" title="Zmniejsz liczbę sztuk produktu w zamówieniu.">-</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        /*Dodaj produkt*/
        echo '<tr><td colspan="5">';
        echo '<div class="option-heading" onclick="ps(this, children);">Dodaj produkt<div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div></div>';
        echo '<div id="" class="option-content">';
        echo '<form method="post"><select name="dnp">';
        $op = '';
        $p = mysqli_query($pol, 'SELECT * FROM produkty');
        while ($rp = mysqli_fetch_object($p))
        {
            $op .= '<option value="'.$rp->id.'">';
            $op .= 'ID: '.$rp->id.', '.$rp->nazwa.', dostępność: '.$rp->dostep.' sztuk, cena/szt: '. str_replace('.',',',$rp->cena).' zł';
            $op .='</option>';
        }
        $p = mysqli_query($pol, 'SELECT * FROM dodatkowe');
        while ($rp = mysqli_fetch_object($p))
        {
            $op .= '<option value="d'.$rp->id.'">';
            $op .= 'ID d: '.$rp->id.', '.$rp->nazwa.', cena/szt: '. str_replace('.',',',$rp->cena).' zł';
            $op .='</option>';
        }
        echo $op.'</select><br/><br/>';
        echo 'Sztuk: <input type="number" name="szt" min="1" step="1" value="1" /><br/><br/>';
        echo '<input type="submit" class="btn" name="dp" value="Dodaj" />';
        echo '</form></div></td></tr>';
        /*Dodaj kod rabatowy*/
        echo '<tr><td colspan="5">';
        echo '<div class="option-heading" onclick="ps(this, children);">Dodaj kod rabatowy<div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div></div>';
        echo '<div id="" class="option-content">';
        echo '<form method="post"><select name="okr">';
        $op = '';
        $p = mysqli_query($pol, 'SELECT * FROM kr');
        while ($rp = mysqli_fetch_object($p))
        {
            $op .= '<option value="'.$rp->id.'">';
            $op .= 'Opis kodu: '.$rp->opis;
            $op .='</option>';
        }
        echo $op.'</select><br/><br/>';
        echo '<input type="submit" class="btn" name="dkr" value="Dodaj" />';
        echo '</form></div></td></tr>';
        /*Dodaj przesyłkę*/
        echo '<tr><td colspan="5">';
        echo '<div class="option-heading" onclick="ps(this, children);">Dodaj przesyłkę<div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div></div>';
        echo '<div id="" class="option-content">';
        echo '<form method="post"><select name="ddw">';
        $op = '';
        $p = mysqli_query($pol, 'SELECT * FROM wysylka WHERE wagamax >= '.$waga.' AND wagamin <='.$waga.' ');
        while ($rp = mysqli_fetch_object($p))
        {
            $op .= '<option value="'.$rp->id.'">';
            $op .= 'Typ: '.$rp->typ.', ';
            $op .= 'Cena: '.str_replace('.',',',$rp->cena). ' zł';
            $op .='</option>';
        }
        echo $op.'</select><br/><br/>';
        echo '<input type="submit" class="btn" name="dd" value="Dodaj" />';
        echo '</form></div></td></tr>';
        echo '</tbody></table>';
        echo '<br/>ID Klienta: <input type="text" class="tb" name="idklienta" value="' . $rz->id_uzyt . '"><br/>' .
        'Uwagi:<br/><textarea class="tb" name="uwagi">' . $rz->uwagi . '</textarea><br/>
        <br/><input class="btn" type="submit" name="zez" value="Zapisz"> <input class="btn" type="submit" name="aez" value="Anuluj"><br/>
        
    </form>
</div>';
    }
}

if (isset($_POST['aez']))
{
    header("Location: admin.php?karta=0");
}

if (isset($_POST['dp']))
{
    header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&dp=".$_POST['dnp']."&szt=".$_POST['szt']);
}

if (isset($_POST['dkr']))
{
    header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&dkr=".$_POST['okr']);
}

if (isset($_POST['dd']))
{
    header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&ddw=".$_POST['ddw']);
}

if (isset($_GET['dodaj']))
{
    header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&dodaj=".$_GET['dodaj']);
}

if (isset($_GET['odejmij']))
{
    header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&odejmij=".$_GET['odejmij']);
}

if (isset($_GET['idz']) && isset($_SESSION['login_a']) && isset($_GET['usun']))
{
        header("Location: edytujkoszykz.php?idz=".$_GET['idz']."&usun=".$_GET['usun']);
}

$q = ' ';
if (isset($_SESSION['login_a']) && !isset($_GET['idz'])) {
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
        $q = 'WHERE MONTH(data)=' . $_POST['mies'] . ' AND YEAR(data)=' . $_POST['rok'];
    }
    if (isset($_POST['mies'])) {
        switch ($_POST['mies']) {
            case 0: $sm0 = "selected";
                break;
            case 1: $sm1 = "selected";
                break;
            case 2: $sm2 = "selected";
                break;
            case 3: $sm3 = "selected";
                break;
            case 4: $sm4 = "selected";
                break;
            case 5: $sm5 = "selected";
                break;
            case 6: $sm6 = "selected";
                break;
            case 7: $sm7 = "selected";
                break;
            case 8: $sm8 = "selected";
                break;
            case 9: $sm9 = "selected";
                break;
            case 10: $sm10 = "selected";
                break;
            case 11: $sm11 = "selected";
                break;
            case 12: $sm12 = "selected";
                break;
            default:;
                break;
        }
    }
    if (isset($_POST['rok'])) {
        switch ($_POST['rok']) {
            case 0: $sr0 = "selected";
                break;
            case 2017: $sr1 = "selected";
                break;
            case 2018: $sr2 = "selected";
                break;
            case 2019: $sr3 = "selected";
                break;
        }
    }

    if (isset($_POST['nrk']))
    {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_POST['nrk'] != '')
        {
            $q = 'WHERE id_uzyt ='.$_POST['nrk'];
        }
        unset($_POST['nrk']);
    }

    if (isset($_POST['nrz']))
    {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_POST['nrz'] != '')
        {
            $q = 'WHERE id='.$_POST['nrz'];
        }
        unset($_POST['nrz']);
    }
    if (isset($_GET['nrz']))
    {
        unset($_POST['rok']);
        unset($_POST['mies']);
        if ($_GET['nrz'] != '')
        {
            $q = 'WHERE id='.$_GET['nrz'];
        }
        echo '<form action="" method="post"><input class="btn" type="submit" name="rsf" value="Usuń filtry" /></form>';
        $vnrz = $_GET['nrz'];
    }
    else
    {
        $vnrz = '';
    }
    if (isset($_POST['rsf']))
    {
        unset($_POST['nrk']);
        unset($_POST['nrz']);
        unset($_POST['rsf']);
        header("Location: admin.php?karta=0");
    }



    echo '<label>Fitruj zamówienia:</label><form action="" style="position: relative;" method="post">
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
    <label>lub Numer zamówienia: </label><input type="text" class="tb" name="nrz" value="'.$vnrz.'"/>
    <input class="btn" type="submit" name="fpdata" value="Pokaż" />
</form><br/>';

    $zr = mysqli_query($pol, 'SELECT * FROM lz '.$q);
    //echo '$q = '.$q.'<br/><br/>';
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
                                    <th> Opcje </th>
                                </tr>
                            </thead>
                            <tbody>';
    if (mysqli_num_rows($zr) > 0) {
        while ($zz = mysqli_fetch_object($zr)) {
            $sumaz = 0.00;
            echo '<tr>';
            echo '<td><label>' . $zz->id . '</label></td>';
            $dataz = date_create($zz->data);
            echo '<td><label>' . str_replace(" ", "<br/>",date_format($dataz,"d.m.Y H:i:s") ) . '</label></td>';
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
                $sumaz += $kz[$i]->cena * $kz[$i]->sztuk;
                echo '</tr>';
            }
            echo '<tr><td colspan="3"><b>Suma zamówienia: </b></td><td><b>'.number_format($sumaz, 2, ',', ' ') . ' zł</b></td></tr>';
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
            echo '<td ><form id="opdz" method="post" action="admin.php?karta=0&idz=' . $zz->id . '"><input type="submit" class="btn" value="Edytuj" name="edytujz" /></form>';
            $adfk = mysqli_query($pol, "SELECT * FROM lak WHERE id_uzyt=" . $klient . " AND adresf <> ''");
            while ($wef = mysqli_fetch_object($adfk)) {
                if (!empty($wef->adresf)) {
                    echo '<form id="opdz" method="post" action="admin.php?karta=0&"><input type="submit" class="btn" value="Generuj fakturę" name="fakturaz" /></form>';
                }
            }
            echo '<form id="opdz" method="post" action="admin.php?karta=0&"><input type="submit" class="btn" value="Generuj ELP" name="listz" /></form>';
            echo '</td>';
            echo '</tr>';
        }
    }
    else 
    {
        echo '<tr><td colspan="8">Brak zamówień z tego okresu.</td></tr>';
    }
    echo '</tbody></table><br/>';
}
?>