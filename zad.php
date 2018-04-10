<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();

$ad = "";

require 'polacz.php';
$q = mysqli_query($pol,"SELECT * FROM lak WHERE id_uzyt=".$_SESSION['idk']);
$aq = mysqli_fetch_object($q);
$ad = $aq->adres;
echo '<h3>Zmiana adresu do wysyłki:</h3>';
echo '<form method="post" action="zad.php">
<label style="font-size: medium;">Nowy adres:</label><br/><br/>
<textarea class="tb" rows="4" cols="22" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ul. Ulica 100/101 &nbsp;&nbsp;&nbsp;&nbsp; 00-001 Miasto &nbsp;&nbsp;" name="adresd">'.$ad.'</textarea><br/><br/>
<input type="submit" class="btn" value="Zmień" name="zzad" /> <input type="submit" class="btn" value="Anuluj" name="anuluj"/>
</form>';
echo '';
if (isset($_POST['anuluj']))
{
    unset($_POST['zad']);
    unset($_POST['anuluj']);
    unset($_POST['adresf']);
    header("Location: konto.php");
}
if (isset($_POST['zzad']))
{
    mysqli_query($pol,"UPDATE lak SET adres = '".$_POST['adresd']."' WHERE id_uzyt =".$_SESSION['idk']);
    unset($_POST['zzad']);
    unset($_POST['anuluj']);
    unset($_POST['adresd']);
    header("Location: konto.php");
}
?>