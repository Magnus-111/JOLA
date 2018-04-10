<?php

function wyswietl($id) {
    require '../polacz.php';
    echo '<table class="tbl" border="1" cellspacing="2" cellpadding="3"><thead><tr><th>Zdjęcie</th><th>Opcje</th></tr></thead><tbody>';
    $r = mysqli_query($pol, "SELECT * FROM obr WHERE id_prod=".$id." ORDER BY id ASC");
    while ($rw = mysqli_fetch_object($r)) {
        echo '<tr>';
        echo '<td><img height="120" width="120" style="padding: 1px 0px; margin: 0px -160px;" src="pimg/'.$rw->nazwa.'" /></td>';
        echo '<td><a title="Usuń zdjęcie" href="edytujprodukt.php?uzd='.$rw->nazwa.'&idp='.$id.'"><img width="32px" height="32px" src="../ikon/usun.png"></a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

require '../polacz.php';

if (isset($_POST['dz']))
{
    if (isset($_FILES['dimgs']))
    {
        foreach ($_FILES['dimgs']['name'] as $k => $n)
        {
            $fn = $n;
            try
            {
                move_uploaded_file($_FILES['dimgs']['tmp_name'][$k], 'pimg/'.$fn);
                mysqli_query($pol, 'INSERT INTO obr VALUES(NULL,"'.$fn.'",'.$_GET['idp'].')');
            }
            catch (Exception $e)
            {
                echo $e;
            }
        }
    }
    header("Location: admin.php?karta=1&idp=".$_GET['idp']);
}

if (isset($_POST['anuluj']))
{
    header("Location: admin.php?karta=1");
}
if (isset($_GET['uzd']) && isset($_GET['idp']))
{
    mysqli_query($pol, 'DELETE FROM obr WHERE nazwa="'.$_GET['uzd'].'" AND id_prod='.$_GET['idp']);
    unlink("pimg/".$_GET['uzd']);
    header("Location: admin.php?karta=1&idp=".$_GET['idp']);
}

if (isset($_POST['zapisz'])) {
    $cena = str_replace(",", ".", $_POST['cena']);
    $waga = str_replace(",", ".", $_POST['waga']);
    mysqli_query($pol, 'UPDATE `produkty` SET `nazwa` ="' . $_POST['nazwa'] . '" WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `dostep` =' . $_POST['sztuk'] . ' WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `cena` =' . $cena . ' WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `waga` =' . $waga . ' WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `opis` ="' . $_POST['opis'] . '" WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `material` ="' . $_POST['material'] . '" WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    mysqli_query($pol, 'UPDATE `produkty` SET `typ` ="' . $_POST['typ'] . '" WHERE `produkty`.`id` =' . $_GET['idp'].' ');
    header("Location: admin.php?karta=1");
}
if (isset($_GET['idp']) && isset($_SESSION['login_a'])) {
    $_SESSION['idp'] = $_GET['idp'];
    $c = '';
    $p = mysqli_query($pol, "SELECT * FROM `produkty` WHERE id =" . $_GET['idp']);
    echo '<form action="" method="post">';
    while ($ep = mysqli_fetch_object($p)) {
        $ac = 'Cena: <input type="number" name="cena" value="' . $ep->cena . '" min="0.01" step="0.01">&nbsp;<input type="submit" name="pc" class="btn" value="Przecena" /><br/>';
        $pc = 'Stara cena: <label>'.number_format($ep->scena, 2, ',', ' ') . ' zł'.'&nbsp;</label><input type="submit" name="ac" class="btn" value="Anuluj przecene" /><br/>'
        . 'Aktualna cena: <input type="number" name="cena" value="' . $ep->cena . '" min="0.01" step="0.01"><br/>';
        $opt0 = $opm0 = "";
        $opt1 = $opm1 = "";
        $opt2 = $opm2 = "";
        $opt3 = $opm3 = "";
        $opt4 = $opm4 = "";
        switch ($ep->typ) {
            case 'Buty': $opt1 = "selected"; break;
            case 'Portfele': $opt2 = "selected"; break;
            case 'Torebki': $opt3 = "selected"; break;
            case 'Dodatki': $opt4 = "selected"; break;
            default: $opt0 = "selected"; break;
        }
        switch ($ep->material) {
            case 'Naturalna skóra': $opm1 = "selected"; break;
            case 'Sztuczna skóra': $opm2 = "selected"; break;
            case 'Tkanina': $opm3 = "selected"; break;
            case 'Inny': $opm4 = "selected"; break;
            default: $opm0 = "selected"; break;
        }
        if (isset($_POST['pc']))
        {
            require '../polacz.php';
            mysqli_query($pol, 'UPDATE `produkty` SET `scena` ='.$ep->cena.' WHERE `produkty`.`id` =' . $_GET['idp'].' ');
            header("Location: ");
        }
        if (isset($_POST['ac']))
        {
            require '../polacz.php';
            mysqli_query($pol,'UPDATE `produkty` SET `cena` ='.$ep->scena.' WHERE `produkty`.`id` ='.$_GET['idp'].' ');
            mysqli_query($pol,'UPDATE `produkty` SET `scena` = 0 WHERE `produkty`.`id` ='.$_GET['idp'].' ');
            mysqli_query($pol,'UPDATE `produkty` SET `scena` = NULL WHERE `produkty`.`id` ='.$_GET['idp'].' ');
            header("Location: ");
        }
        if ($ep->scena == NULL) {
            $c = $ac;
        } else {
            $c = $pc;
        }
        echo 'ID produktu: <label name="idp">' . $ep->id . '</label><br/>' .
        'Nazwa: <input type="text" class="tb" name="nazwa" value="' . $ep->nazwa . '"><br/>'
        . 'Ilość sztuk: <input type="number" name="sztuk" value="' . $ep->dostep . '" min="0" step="1"><br/>'
        . $c
        . 'Waga: <input type="number" name="waga" value="' . $ep->waga . '" min="0.01" step="0.01"><br/>'
        . 'Opis: <textarea class="tb" name="opis">' . $ep->opis . '</textarea><br/>'
        . 'Materiał: <select name="material">
                <option value=" " '.$opm0.' >Wybierz</option>
                <option value="Naturalna skóra" '.$opm1.' >Naturalna skóra</option>
                <option value="Sztuczna skóra" '.$opm2.' >Sztuczna skóra</option>
                <option value="Tkanina" '.$opm3.' >Tkanina</option>
                <option value="Inny" '.$opm4.' >Inny</option>
            </select><br/>'
        . 'Typ: <select name="typ">
                    <option value=" " ' . $opt0 . '>Wybierz</option>
                    <option value="Buty" ' . $opt1 . '>Buty</option>
                    <option value="Portfele" ' . $opt2 . '>Portfele</option>
                    <option value="Torebki" ' . $opt3 . '>Torebki</option>
                    <option value="Dodatki" ' . $opt4 . '>Dodatki</option>
                </select><br/><br/>';

    }
    echo '<input type="submit" class="btn" name="zapisz" value="Zapisz">  '
    . '<input type="submit" class="btn" name="anuluj" value="Anuluj"><br/><br/>'
    . '</form>';
    echo '<h2>Zdjęcia: </h2>';
    echo '<form method="post" action="" enctype="multipart/form-data" accept-charset="UTF-8">
<input type="file" name="dimgs[]" multiple > 
<input type="submit" class="btn" value="Dodaj" name="dz"/>
</form><br/>';
    wyswietl($_GET['idp']);
}


?>
