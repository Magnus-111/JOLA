<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();
require '../polacz.php';
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="styla.css">
        <script type="text/javascript" src="../skrypt.js" ></script>
        <title>.: JOLA - Panel administracyjny :.</title>
    </head>
    <body >
        <div>
            <a href="../index.php">Wróć do sklepu</a>
        </div>
        <div id="pla">
            <?php
            if (isset($_POST['btn_login_a'])) {

                $email = strip_tags($_POST['login_a']);
                $haslo = strip_tags($_POST['haslo_a']);
                $email = stripslashes($email);
                $haslo = stripslashes($haslo);
                $email = mysqli_real_escape_string($pol, $email);
                $haslo = mysqli_real_escape_string($pol, $haslo);

                $sql = "SELECT * FROM lka WHERE login='$email' LIMIT 1";
                $pyt = mysqli_query($pol, $sql);
                $w = mysqli_fetch_array($pyt);
                $id = $w['id'];
                $db_haslo = $w['haslo'];

                if (empty($email)) {
                    echo '<div class="error">Nie wprowadzono loginu administatora!!!</div>';
                }
                if (empty($haslo)) {
                    echo '<div class="error">Nie wprowadzono hasła!!!</div>';
                }
                if (password_verify($haslo, $db_haslo) && !empty($email)) {
                    $_SESSION['login_a'] = $email;
                } else {
                    echo '<div class="error">Błąd logowania!!!</div>';
                }
            }

            if (isset($_SESSION['login_a'])) {
                header('Location: admin.php');
            }

            if (isset($_POST['btn_reg_a'])) {

            }
            ?>
            <label><b>Panel administracyjny:</b></label><br/></br>
            <label><b><i>Logowanie:</i></b></label><br/><br/>
            <form style="margin-left: -38px;" method="post" action="" enctype="multipart/form-data" accept-charset="UTF-8">
                <label><i>Login:</i></label><input class="tb" name="login_a" type="text"><br/>
                <label><i>Hasło:</i></label><input class="tb" name="haslo_a" type="password"><br/>
                <input class="btn" name="btn_login_a" type="submit" value="Zaloguj"><br/>
            </form>
        </div>
    </body>
</html>
