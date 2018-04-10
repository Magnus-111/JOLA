<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 11.05.2017
 * Time: 20:02
 */
ob_start();
require '../polacz.php';

if (isset($_POST['btn_reg_a']))
{
    $login = $_POST['reg_login_a'];
    $email = $_POST['reg_email_a'];
    $haslo = $_POST['reg_haslo_a'];
    $hhaslo = password_hash($haslo, PASSWORD_DEFAULT);
    $hasloI = $_POST['haslo_pierwszy_a'];
    $hhasloI = password_hash($hasloI, PASSWORD_DEFAULT);
    mysqli_query($pol, "INSERT INTO lka VALUES (NULL,'".$login."','".$hhaslo."','".$email."')");
    header("Location: admin.php?karta=3");
}
if (isset($_POST['dodajkm']))
{
    echo '<form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                    <label>Hasło pierwszego administratora: </label><input class="tb" name="haslo_pierwszy_a" type="text"><br/>
                    <label>Login: </label><input class="tb" name="reg_login_a" type="text"><br/>
                    <label>E-mail: </label><input class="tb" name="reg_email_a" type="text"><br/>
                    <label>Hasło: </label><input class="tb" name="reg_haslo_a" type="password"><br/>
                    <input class="btn" name="btn_reg_a" type="submit" value="Utwórz">&nbsp;&nbsp;<input type="submit" class="btn" value="Anuluj" name="anulujkm"/><br/>
                </form>';
}
if (isset($_GET['usunkm']))
{
    mysqli_query($pol,"DELETE FROM lka WHERE id=".$_GET['usunkm']);
    header("Location: admin.php?karta=3");
}

if (isset($_POST['anulujkm'])) {
    header("Location: admin.php?karta=3");
}

if (isset($_POST['zapiszkm'])) {
    mysqli_query($pol,"UPDATE lka SET login = '".$_POST['loginkm']."' WHERE id =".$_GET['edytujkm']);
    mysqli_query($pol,"UPDATE lka SET email = '".$_POST['emailkm']."' WHERE id =".$_GET['edytujkm']);
    $Hhaslo = password_hash($_POST['haslokm'], PASSWORD_DEFAULT);
    mysqli_query($pol,"UPDATE lka SET haslo = '".$Hhaslo."' WHERE id =".$_GET['edytujkm']);
    header("Location: admin.php?karta=3");
}

if (isset($_GET['edytujkm']))
{
    $kq = mysqli_query($pol,"SELECT * FROM lka WHERE id=".$_GET['edytujkm']);
    $okq = mysqli_fetch_object($kq);
    echo '<form method="post">
        <label>Login: </label><input type="text" class="tb" name="loginkm" value="'.$okq->login.'"/><br/>
        <label>E-mail: </label><input type="text" class="tb" name="emailkm" value="'.$okq->email.'"/><br/>
        <label>Nowe hasło: </label><input type="password" class="tb" name="haslokm" value="'.$okq->haslo.'"/><br/>
        <input type="submit" class="btn" value="Zapisz" name="zapiszkm"/>&nbsp;&nbsp;
        <input type="submit" class="btn" value="Anuluj" name="anulujkm"/>
        </form>';
}
if (isset($_SESSION['login_a']))
{
        echo '<form method="post" ><input type="submit" class="btn" value="Dodaj konto" name="dodajkm" /></form>';
        $q = mysqli_query($pol, 'SELECT * FROM lka');
        echo '<table class="tbl" border="2" cellspacing="3" cellpadding="5"><thead><tr><th> ID </th><th> Login </th><th> E-mail </th><th> Opcje </th></tr></thead><tbody>';
        if (mysqli_num_rows($q) > 0) {
            while ($kt = mysqli_fetch_object($q)) {
                echo '<tr>';
                echo '<td>' . $kt->id . '</td>';
                echo '<td>' . $kt->login . '</td>';
                echo '<td>' . $kt->email . '</td>';
                echo '<td xmlns="http://www.w3.org/1999/html"><label><form method="post" action="admin.php?karta=3&edytujkm='.$kt->id.'"><input type="submit" class="btn" value="Edytuj" /> <a href="admin.php?karta=3&usunkm='.$kt->id.'" title="Usuń konto"><img src="../ikon/usun.png" height="20"></a></form></label></td>';
                echo '</tr>';
            }
        }
        echo '</tbody></table>';
}
else
{
    header("Location: index.php");
}

?>