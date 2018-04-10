<?php
/*
 * User: Cezary
 */
require 'polacz.php';
if (isset($_POST['wrma']))
{
    $sel = explode(";",$_POST['prma']);
    //echo $sel[0].' '.$sel[1];
    mysqli_query($pol,"INSERT INTO rma VALUES( NULL, NOW() , DEFAULT , ".$sel[0]." , ".$sel[1]." , '".$_POST['opis']."' , '".$_POST['uwagi']."' , ".$_SESSION['idk'].") ");
    //mysqli_query($pol,"INSERT INTO rma VALUES(NULL,NOW(),DEFAULT, 1, 1,'gdgfgd','hynhbngf', 1);");
}
echo '<div class="option-heading" onclick="ps(this,children);"><b>Dodaj zgłoszenie:</b><div class="arrow-up">&#9650;</div><div class="arrow-down">&#9660;</div></div>';
echo '<div class="option-content">';
echo  '<form method="post" action="">';
echo 'Wybierz produkt: <div class="select"><select name="prma">';
$result = mysqli_query($pol, "SELECT * FROM lz WHERE id_uzyt=" . $_SESSION['idk'] ." AND status = 5 ");
if (mysqli_num_rows($result) > 0) {
    echo '<option value="0;0">Wybierz produkt</option>';
    while ($zam = mysqli_fetch_object($result)) {
        $zk = unserialize($zam->koszyk);
        for ($i = 0; $i < count($zk); $i++) {
            if (substr($zk[$i]->id,0,1) != "p")
            {
                echo '<option value="'.$zk[$i]->id.';'.$zam->id .'"> (Numer zamówienia: '.$zam->id.')  '. $zk[$i]->nazwa .'</option>';
            }
        }
    }
}
else
{
    echo '<option>Brak kupionych produktów.</option>';
}
echo '</select><div class="select__arrow"></div></div><br/>';
echo 'Usterki:&nbsp;&nbsp;<textarea name="opis" /></textarea>&nbsp;&nbsp;';
echo 'Uwagi:&nbsp;&nbsp;<textarea name="uwagi" /></textarea>&nbsp;&nbsp;&nbsp;';
echo '<input class="btn" type="submit" name="wrma" value="Wyślij"/><br/>';
echo '</form></div><br/>';
echo '<h3>Twoje reklamacje i zwroty</h3>';
echo '<br/><table class="tabela" border="0" cellspacing="3" cellpadding="6">';
echo '<thead><th>&nbsp;Numer zgłoszenia&nbsp;</th><th>&nbsp;Data&nbsp;</th><th>&nbsp;Status&nbsp;</th><th>&nbsp;Produkt&nbsp;</th><th>&nbsp;Nr zamówienia&nbsp;</th><th>&nbsp;Opis&nbsp;</th><th>&nbsp;Uwagi&nbsp;</th></thead><tbody>';
$rez = mysqli_query($pol,'SELECT * FROM rma WHERE id_uzyt='.$_SESSION['idk']);
if (mysqli_num_rows($rez) > 0)
{
    while($rma = mysqli_fetch_object($rez))
    {
        $pr = mysqli_query($pol, 'SELECT * FROM produkty WHERE id='.$rma->id_prod.'');
        $produkt = mysqli_fetch_object($pr);
        echo '<tr>';
        echo '<td><label>'.$rma->id.'</label></td>';
        echo '<td><label>'.$rma->data.'</label></td>';
        echo '<td><label>'.$rma->status.'</label></td>';
        echo '<td><label>'.$produkt->nazwa.'</label></td>';
        echo '<td><label>'.$rma->id_zam.'</label></td>';
        echo '<td><label>'.$rma->opis.'</label></td>';
        echo '<td><label>'.$rma->uwagi.'</label></td>';
        echo '</tr>';
    }
}
else
{
    echo '<tr><td colspan="6">Brak zgłoszeń.</td></tr>';
}
echo '</tbody></table><br/>';
?>