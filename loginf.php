<?php

if (!isset($_SESSION['lbl'])) {
    $_SESSION['lbl'] = 0;
}
if (isset($_POST['btn_login'])) {
    require 'polacz.php';
    $email = strip_tags($_POST['email']);
    $haslo = strip_tags($_POST['haslo']);
    $email = stripslashes($email);
    $haslo = stripslashes($haslo);
    $email = mysqli_real_escape_string($pol, $email);
    $haslo = mysqli_real_escape_string($pol, $haslo);

    $sql = "SELECT * FROM lkk WHERE email='$email' LIMIT 1";
    $pyt = mysqli_query($pol, $sql);
    $w = mysqli_fetch_array($pyt);
    $id = $w['id_uzyt'];
    $db_haslo = $w['haslo'];


    if (!empty($email) && !password_verify($haslo, $db_haslo)) {
        $_SESSION['lbl']++;
    }

    if (password_verify($haslo, $db_haslo) && !empty($email) && !empty($haslo)) {
        $_SESSION['email'] = $email;
        $_SESSION['idk'] = $id;
        header("Location: ");
    } else {
            $_SESSION['e_logowania'] = 'Błąd logowania!!! Nie poprawny e-mail lub hasło.';
            echo '<script> pum(\'#paseklogowania\');</script>';
            echo '<script>pum(\'#paseklr\');</script>';
            $_SESSION['lbl']++;
        }
    unset($_POST['btn_login']);
}

if (isset($_POST['btn_logout'])) {
    unset($_SESSION['email']);
    unset($_SESSION['idk']);

    if (isset($_SESSION['astr'])) {
        if ($_SESSION['astr'] != "konto") {
            header("Location: ");
        }
        else
        {
            header("Location: lr.php");
        }
    }
    else
    {
        //echo '<script>pum(\'paseklr\');</script>';
        header("Location: index.php");
    }

}
?>