<?php

/*
 * User: Cezary
 */
if (!isset($_SESSION)) {
    session_start();
}
ob_start();

require 'polacz.php';
echo '<h3>Zmiana numeru telefonu:</h3>';
echo '<form action="zt.php" method="post">'.'<label>Nowy numer: </label><input type="text" class="tb" name="ntelefon" /><br/>';
echo '<input type="submit" class="btn" name="zzt" value="Zmień" /> &nbsp; &nbsp;'. '<input type="submit" class="btn" name="zta" value="Anuluj" />'.'</form>';
if (isset($_POST['zzt'])) {
    mysqli_query($pol, "UPDATE `lak` SET `telefon`= '". $_POST['ntelefon'] . "' WHERE `id_uzyt` = " . $_SESSION['idk']."");
    unset($_POST['zzt']);
    unset($_POST['znt']);
    header("Location: konto.php");
}
if (isset($_POST['zta']))
{
    unset($_POST['zzt']);
    unset($_POST['znt']);
    unset($_POST['zta']);
    header("Location: konto.php");
}

?>
