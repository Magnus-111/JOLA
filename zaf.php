<?php
/*
 * User: Cezary
 */

if (!isset($_SESSION)) {
    session_start();
}
ob_start();

$af = "";

require 'polacz.php';
$q = mysqli_query($pol,"SELECT * FROM lak WHERE id_uzyt=".$_SESSION['idk']);
$aq = mysqli_fetch_object($q);
if ($aq->adresf != NULL)
{
    $af = $aq->adresf;
}
echo '<h3>Zmiana adresu do faktury:</h3>';
echo '<form method="post" action="zaf.php">
<label style="font-size: medium;">Nowy adres:</label><br/><br/>
<textarea class="tb" rows="4" cols="22" placeholder="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nazwa firmy &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NIP: 001-005-20-10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ul. Ulica 100/101 &nbsp;&nbsp;&nbsp;&nbsp; 00-001 Miasto &nbsp;&nbsp;" name="adresf">'.$af.'</textarea><br/><br/>
<input type="submit" class="btn" value="Zmień" name="zzaf" /> <input type="submit" class="btn" value="Anuluj" name="anuluj"/>
</form>';
echo '';
if (isset($_POST['anuluj']))
{
    unset($_POST['zaf']);
    unset($_POST['anuluj']);
    unset($_POST['adresf']);
    header("Location: konto.php");
}
if (isset($_POST['zzaf']))
{
    mysqli_query($pol,"UPDATE lak SET adresf = '".$_POST['adresf']."' WHERE id_uzyt =".$_SESSION['idk']);
    unset($_POST['zzaf']);
    unset($_POST['anuluj']);
    unset($_POST['adresf']);
    header("Location: konto.php");
}
?>