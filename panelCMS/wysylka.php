<?php
/*
 * User: Cezary
 */

if (isset($_GET['usun']))
{
    mysqli_query($pol,'DELETE FROM wysylka WHERE id='.$_GET['usun'].'');
    header("Location: admin.php?karta=7");
}

if (isset($_POST['anuluj']))
{
    unset($_POST['anuluj']);
    header("Location: admin.php?karta=7");
}

if (isset($_POST['zapisz']) && isset($_GET['idw']))
{
    mysqli_query($pol, "UPDATE wysylka SET typ='".$_POST['nazwa']."' WHERE id=".$_GET['idw']." ");
    mysqli_query($pol, "UPDATE wysylka SET wagamin=".$_POST['wmin']." WHERE id=".$_GET['idw']." ");
    mysqli_query($pol, "UPDATE wysylka SET wagamax=".$_POST['wmax']." WHERE id=".$_GET['idw']." ");
    unset($_POST['zapisz']);
    header("Location: admin.php?karta=7");
}

if (isset($_GET['dodajf']))
{
    echo '<h3>Dodawanie usługi numer:</h3>';
    echo '<form method="post" action="">
    <label>Firma (typ): </label>
        <select name="dnazwa">
            <option value="Odbiór osobisty">Odbiór osobisty</option>
            <option value="DPD" >DPD</option>
            <option value="InPost">InPost</option>
        </select>
        <br/><br/>
    <label>Waga minimalna: </label><input type="number" name="dwmin" min="0" step="0.01" /> kg<br/><br/>
    <label>Waga maksymalna: </label><input type="number" name="dwmax" min="0.01" step="0.01" /> kg<br/><br/>
    <label>Cena: </label><input type="number" name="dcena" step="0.01" min="0" /> zł<br/><br/>
    <input type="submit" name="dodaj" class="btn" value="Dodaj"> <input type="submit" class="btn" name="anuluj" value="Anuluj" />
    </form>';
}

if (isset($_POST['dodaj']))
{
    mysqli_query($pol, "INSERT INTO wysylka VALUES (NULL, '".$_POST['dnazwa']."', ".$_POST['dwmin'].", ".$_POST['dwmax'].", ".$_POST['dcena'].")");
    unset($_POST['dodaj']);
    header("Location: admin.php?karta=7");
}

if (isset($_GET['idw']))
{
    $o1 = NULL;
    $o2 = NULL;
    $o3 = NULL;
    echo '<h3>Edycja usługi numer '.$_GET['idw'].':</h3>';
    $eq = mysqli_query($pol,'SELECT * FROM wysylka WHERE id='.$_GET['idw']);
    $oeq = mysqli_fetch_object($eq);
    switch ($oeq->typ)
    {
        case 'DPD': $o2 = 'selected' ; break;
        case 'Odbiór osobisty': $o1 = 'selected'; break;
        case 'InPost': $o3 = 'selected'; break;
    }
    echo '<form method="post" action="admin.php?karta=7&idw='.$oeq->id.'">
    <label>Firma (typ): </label><select name="nazwa"><option value="Odbiór osobisty" '.$o1.'>Odbiór osobisty</option><option value="DPD" '.$o2.'>DPD</option><option value="InPost" '.$o3.'>InPost</option></select><br/><br/>
    <label>Waga minimalna: </label><input type="number" name="wmin" min="0" step="0.01" value="'.$oeq->wagamin.'" /> kg<br/><br/>
    <label>Waga maksymalna: </label><input type="number" name="wmax" min="0.01" step="0.01" value="'.$oeq->wagamax.'" /> kg<br/><br/>
    <label>Cena: </label><input type="number" name="cena" step="0.01" min="0" value="'.$oeq->cena.'" /> zł<br/><br/>
    <input type="submit" name="zapisz" class="btn" value="Zapisz"> <input type="submit" class="btn" name="anuluj" value="Anuluj" />
    </form>';

}
if  (isset($_SESSION['login_a']) && !isset($_POST['edytuj']))
{
    echo '<form method="post" action="admin.php?karta=7&dodajf"><input type="submit" class="btn" value="Dodaj usługę" name=""/></form>';
    echo '<table class="tbl" border="2" cellspacing="4" cellpadding="3"><thead><tr><th>Firma (typ)</th><th>Waga min. zamówienia</th><th>Waga maks. zamówienia</th><th>Cena za usługę</th><th>Opcje</th></th></tr></thead><tbody>';
    $q = mysqli_query($pol, 'SELECT * FROM wysylka');
    while ($oq = mysqli_fetch_object($q))
    {
        echo '<tr>';
        echo '<td>'.$oq->typ.'</td>';
        echo '<td>'.str_replace('.',',', $oq->wagamin).' kg</td>';
        echo '<td>'.str_replace('.',',', $oq->wagamax).' kg</td>';
        echo '<td>'.str_replace('.',',',$oq->cena).' zł</td>';
        echo '<td><a href="admin.php?karta=7&usun='.$oq->id.'" title="Usuń usługę"><img src="../ikon/usun.png" height="18"></a> &nbsp;<form method="post" action="admin.php?karta=7&idw='.$oq->id.'" ><input type="submit" class="btn" name="edytuj" value="Edytuj" /></form></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}
if (!isset($_SESSION['login_a']))
{
    header("Location: index.php");
}

?>