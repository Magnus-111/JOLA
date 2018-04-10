<?php
if (!isset($_SESSION)) {
    session_start();
}
ob_start();
$_SESSION['astr'] = "lr";
include 'polacz.php';

if (isset($_POST['rejestruj'])) {
    $OK = TRUE;
    $email = $_POST['remail'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!empty($_POST['remail'])) {
        if (filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $rezultat = mysqli_query($pol, "SELECT id_uzyt FROM lkk WHERE email='$email'");
            $ile_mail = mysqli_num_rows($rezultat);
            if ($ile_mail > 0) {
                $OK = FALSE;
                $_SESSION['e_email'] = "Ten e-mail ma już przypisane konto.";
            } else {
                if (filter_var($email, FILTER_SANITIZE_EMAIL) == FALSE || ($emailB != $email)) {
                    $OK = FALSE;
                    $_SESSION['e_email'] = "Błędny e-mail";
                }

                $haslo1 = $_POST['rhaslo1'];
                $haslo2 = $_POST['rhaslo2'];
                $telefon = $_POST['rtelefon'];

                if ((strlen($haslo1) < 8) || (strlen($haslo1) > 20)) {
                    $OK = FALSE;
                    $_SESSION['e_haslo'] = "Hasło ma zawierać od 8 do 20 znaków";
                }

                if ($haslo1 != $haslo2) {
                    $OK = FALSE;
                    $_SESSION['e_haslo'] = "Podane hasła są różne";
                }

                if (strlen($telefon) != 9) {
                    $OK = FALSE;
                    $_SESSION['e_telefon'] = "Błędny numer telefonu";
                }

                $hhaslo = password_hash($haslo1, PASSWORD_DEFAULT);

                if (!isset($_POST['rregulamin'])) {
                    $OK = FALSE;
                    $_SESSION['e_regulamin'] = "Niezaakceptowano regulaminu";
                }

                $imie = $_POST['rimie'];

                if (empty($imie)) {
                    $OK = FALSE;
                    $_SESSION['e_imie'] = "Nie podano imienia";
                }

                $nazwisko = $_POST['rnazwisko'];

                if (empty($nazwisko)) {
                    $OK = FALSE;
                    $_SESSION['e_nazwisko'] = "Nie podano nazwiska";
                }


                $adres = $_POST['radres'];

                if (empty($adres)) {
                    $OK = FALSE;
                    $_SESSION['e_adres'] = "Nie podano adresu";
                }

                $adresf = $_POST['radresf'];

                if (empty($_POST['rfvat'])) {
                    $adresf = NULL;
                } else {
                    if (empty($adresf)) {
                        $OK = FALSE;
                        $_SESSION['e_adresf'] = "Nie podano adresu";
                    }
                }

                function CheckCaptcha($userResponse)
                {
                    $fields_string = '';
                    $fields = array(
                        'secret' => '6Lc8aEAUAAAAAB66WMOVlD3pcjo-lMawVHELC7mP',
                        'response' => $userResponse
                    );
                    foreach ($fields as $key => $value)
                        $fields_string .= $key . '=' . $value . '&';
                    $fields_string = rtrim($fields_string, '&');
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
                    $res = curl_exec($ch);
                    curl_close($ch);
                    return json_decode($res, true);
                }

                $resReCaptcha = CheckCaptcha($_POST['g-recaptcha-response']);

                if (!$resReCaptcha['success']) {
                    $OK = false;
                    $_SESSION['e_captcha'] = "Nie zaznaczono Google Recaptcha !!!";
                }

                if ($OK) {
                    $_SESSION['emailr'] = $email;
                    mysqli_query($pol, "INSERT INTO lkk VALUES (NULL,'" . $email . "','" . $hhaslo . "',NOW(),DEFAULT ,DEFAULT)");
                    $idnk = mysqli_query($pol, "SELECT * FROM lkk WHERE email = '" . $email . "' ");
                    $nk = NULL;
                    while ($idk = mysqli_fetch_object($idnk)) {
                        $nk = $idk->id_uzyt;
                    }
                    mysqli_query($pol, "INSERT INTO lak VALUES (NULL,'" . $imie . "','" . $nazwisko . "','" . $adres . "','" . $adresf . "','" . $telefon . "'," . $nk . ")");
                    $_SESSION['udana'] = true;
                    header("Location: witamy.php");
                }
            }
        }
    } else {
        $_SESSION['e_email'] = "Niepodano adresu e-mail";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Logowanie</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <link rel="stylesheet" type="text/css" href="styl.css"/>
    <script type="text/javascript" src="skrypt.js"></script>
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <style>
        #kontener #zawartosc .tb, #kontener > #zawartosc > div > form > #ar > .tb {
            width: 50%;
        }
    </style>
</head>
<body>
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div style="margin: 12px auto; padding: 16px 24px; background-color: #fff; text-align: center;">
            <h2>Logowanie</h2>
            <?php
            require 'loginf.php';
            $fl = '<form action="konto.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                        <br/><input class="tb" type="text" name="email" placeholder="E-mail" /><br/><br/>
                        <input class="tb" type="password" name="haslo" placeholder="Hasło" /><br/><br/>
                        <input type="submit" name="btn_login" class="btn" value="Zaloguj się" /><br/>
                    </form><br/><a href="ph.php">Nie pamiętam hasła</a>';
            if (isset($_SESSION['email'])) {
                if ($_SESSION['email'] != NULL) {
                    echo '<div class="error">Już jesteś zalogowany</div>';
                    echo '<form name="loginbar" action="" method="post" style="margin: 5px 8px;" enctype="multipart/form-data" accept-charset="UTF-8">
                          <br/><input type="submit" class="btn" style="margin: -3px 4px;" value="Wyloguj" name="btn_logout" /></form>';

                } else {
                    echo $fl;
                }
            } else {
                echo $fl;
            }
            ?>
        </div>
        <div style="display: inline-block; margin: 12px auto; padding: 16px 24px; width: 100%;  background-color: #fff; text-align: center;">
            <h2>Rejestracja</h2>
            <br/>
            <form action="" method="post" accept-charset="utf-8">
                <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                    unset($_SESSION['e_email']);
                }
                ?>
                <input type="text" name="remail" class="tb" placeholder="E-mail"/>
                <?php
                if (isset($_SESSION['e_haslo'])) {
                    echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
                    unset($_SESSION['e_haslo']);
                } else {
                    echo '<br/><br/>';
                }
                ?>
                <input type="password" class="tb" name="rhaslo1" placeholder="Hasło"/>
                <br/><br/>
                <input type="password" name="rhaslo2" class="tb" placeholder="Powtórz hasło"/>
                <?php
                if (isset($_SESSION['e_imie'])) {
                    echo '<div class="error">' . $_SESSION['e_imie'] . '</div>';
                    unset($_SESSION['e_imie']);
                } else {
                    echo '<br/><br/>';
                }
                ?>
                <input type="text" name="rimie" class="tb" placeholder="Imię / Imiona"/>
                <?php
                if (isset($_SESSION['e_nazwisko'])) {
                    echo '<div class="error">' . $_SESSION['e_nazwisko'] . '</div>';
                    unset($_SESSION['e_nazwisko']);
                } else {
                    echo '<br/><br/>';
                }
                ?>
                <input type="text" name="rnazwisko" class="tb" placeholder="Nazwisko"/>
                <?php
                if (isset($_SESSION['e_telefon'])) {
                    echo '<div class="error">' . $_SESSION['e_telefon'] . '</div>';
                    unset($_SESSION['e_telefon']);
                } else {
                    echo '<br/><br/>';
                }
                ?>
                <input type="tel" class="tb" name="rtelefon" placeholder="Numer telefonu"/>
                <?php
                if (isset($_SESSION['e_adres'])) {
                    echo '<div class="error">' . $_SESSION['e_adres'] . '</div>';
                    unset($_SESSION['e_adres']);
                } else {
                    echo '<br/><br/>';
                }
                ?>
                <textarea name="radres" rows="3" cols="18" class="tb"
                          placeholder="Adres dostawy &#10; Przykładowy adres: &#10; ul. Ulica 1/2 &#10; 00-001 Miasto &#10; "></textarea>
                <br/><br/>
                <label class="control control--checkbox">Faktura VAT?<input type="checkbox" name="rfvat"
                                                                            onchange=" pum('#ar','inline-block'); pum('#arp');"/>
                    <div class="control__indicator"></div>
                </label>
                <?php
                if (isset($_SESSION['e_adref'])) {
                    echo '<div class="error">' . $_SESSION['e_adresf'] . '</div>';
                    unset($_SESSION['e_adresf']);
                }
                ?>
                <div id="arp" style="display: none;"><br/></div>
                <div id="ar" style="display: none;">
                    <textarea name="radresf" class="tb" rows="3" cols="18"
                              placeholder="Dane do faktury VAT &#10; Przykładowy adres: &#10; ul. Ulica 1/2 &#10; 00-001 Miasto &#10; "></textarea>
                </div>
                <?php
                if (isset($_SESSION['e_regulamin'])) {
                    echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div><br/>';
                    unset($_SESSION['e_regulamin']);
                }
                ?>
                <br/><br/>
                <label class="control control--checkbox">Akceptuję &nbsp;<a href="regulamin.php" target="_blank">regulamin</a><input
                            type="checkbox" name="rregulamin"/>
                    <div class="control__indicator"></div>
                </label>
                <?php
                if (isset($_SESSION['e_captcha'])) {
                    echo '<div class="error">' . $_SESSION['e_captcha'] . '</div>';
                    unset($_SESSION['e_captcha']);
                }
                ?>
                <div class="g-recaptcha" data-sitekey="6Lc8aEAUAAAAAKqz3tecyqzHGvCPQJ-BP2Toa6gy"
                     style="width: 300px; position: relative; margin: 20px auto;"></div>
                <input class="btn" type="submit" value="Zarejestruj się" name="rejestruj"/>
            </form>
            <br/>
        </div>
    </div>
    <br/><br/>
</div>
<?php
include 'stopka.php';
?>
<div id="prv-billboard"></div>
</body>
</html>