<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['astr'] = "index";

ob_start();

require 'polacz.php';

function generujhaslo() {
    $rs = substr(sha1(rand(32,255)), 0, 8);
    return $rs;
}

if (isset($_POST['g-recaptcha-response']))
{

    //$zwrotrecaptcha = json_decode($url);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JOLA - Butik internetowy - Przywracanie hasła</title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" type="text/css" href="styl.css" />
    <script type="text/javascript" src="skrypt.js" ></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<?php include 'pasek1.php'; ?>
<div id="kontener">
    <br/>
    <?php include 'belka.php'; ?>
    <?php include 'menu.php'; ?>
    <div id="zawartosc">
        <div style="position: relative; margin: 22px 4px; padding: 42px 26px; background-color: #fff; width: 97%; display: table;">
            <h2>Przywracanie hasła do konta</h2>
            <br/>
            <?php
            if (!isset($_POST['phaslo'])) {
                echo '<h4>Podaj email konto, którym się logujesz.</h4>
                      <form action="ph.php" method="post">
                            <input class="tb" type="text" name="pemail" placeholder="E-mail" />
                            <br/><br/>
                            <div class="g-recaptcha" data-sitekey="6LfEhCkUAAAAAFMXlCuJIvBu2MJfub1a6Q4idNIN"></div><br/>
                            <input class="btn" type="submit" name="phaslo" value="Przywróć" />
                      </form>';
            }
            else
            {
                $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfEhCkUAAAAAKA6OyehFRcBJknDc6yJbfHTlm3n&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']."");
                if (json_decode($url,true)['success'] == 1)
                {
                    $email = $_POST['pemail'];
                    $czyemail = mysqli_query($pol, 'SELECT * FROM lkk WHERE email ="' . $email . '"');
                    if (!empty($email))
                    {
                        if (mysqli_num_rows($czyemail) > 0) {
                            echo "<h1>Na adres e-mail została wysłana wiadomość!!!</h1>";
                            $nh = generujhaslo();
                            //$headers  = "From: testsite < mail@testsite.com >\n";
                            //$headers .= "Cc: testsite < mail@testsite.com >\n";
                            //$headers .= "X-Sender: testsite < mail@testsite.com >\n";
                            $headers = 'X-Mailer: PHP/' . phpversion();
                            $headers .= "X-Priority: 1\n"; // Urgent message!
                            $headers .= "Return-Path: mail@testsite.com\n"; // Return path for errors
                            $headers .= "MIME-Version: 1.0\r\n";
                            $headers .= "Content-Type: text/html; charset=utf-8\n";
                            mysqli_query($pol, 'UPDATE `lkk` SET `haslo` = "' . password_hash($nh, PASSWORD_DEFAULT) . '" WHERE `lkk`.`email` = "' . $email . '"');
                            $ms = '<html lang="pl" style="width: 100%; height: 90%;">
<body style=" background: #dfdfdf; color: #3b3e43; text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; overflow-x: hidden; width: 100%;
              height: 100%; font-size: 16px; font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif; background-attachment: fixed;">
    <div style=" background: rgb(191, 210, 85); background: -webkit-linear-gradient(45deg, rgb(191, 210, 85) 0%, rgb(142, 185, 42) 50%, rgb(114, 170, 0) 51%, rgb(158, 203, 45) 100%);
    background: linear-gradient(45deg, rgb(191, 210, 85) 0%, rgb(142, 185, 42) 50%, rgb(114, 170, 0) 51%, rgb(158, 203, 45) 100%); position: relative; display: block;
    padding: 16px 32px; width: 80%; margin: 25px auto; height: 5%; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <span style="padding: 3px; position: relative; float: left; font-style: italic; font-weight: bold; font-size: xx-large; text-shadow: 3px 3px #c9c9c9; text-decoration: none; width: 100%; height: 100%;">
            <a style="color: #ffffff; text-decoration: none; letter-spacing: 2px;" href="index.php">JOLA</a>
        </span>
    </div>
    <!-- TREŚć-->
    <div style=" background: #ffffff; position: relative; display: block; padding: 16px 32px; width: 80%; margin: 24px auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <h1 style="text-align: center;">Przywracanie hasła dla konta: </h1>
        <h2 style="text-align: center;"> ' . $email . ' </h2>
        <br/>
        <h3 style="text-align: center;">Twoje nowe hasło to: </h3>
        <h1 style="text-align: center; color: #1E90FF;">' . $nh . '</h1>
        <h2 style="text-align: center; font-style: italic;"> Pamiętaj, by po zalogowaniu zmienic hasło.</h2>
        <br/><br/>
        <span style="position: relative; display: block; padding: 10px 16px; margin: 2px auto; background: #ff0000; color: #ffffff; width: 95%;
        text-align: center; font-weight: bolder; font-size: 20px; border: 2px solid #ffffff; border-radius: 18px;">
            <span style="font-style: italic; font-size: 22px;">!!! UWAGA !!!</span><br/><br/> Jeżeli nie klikniołeś/aś przycisku "Przywróć hasło" na naszej stronie, to poinformuj nas telefonicznie o tym emailu.<br/>
            Prawdopodaobnie nasza strona została zhakowana.
        </span>
    </div>
    <!-- STOPKA -->
    <div style="background-color: #c9c9c9; position: relative; display: table; padding: 13px 32px; width: 80%; margin: 2px auto;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <table border="0" cellspacing="0" cellpadding="0">
            <thead style="z-index: -1;">
            <tr style="z-index: -1;">
                <th><label>Zakupy</label></th>
                <th><label>Twoje konto</label></th>
                <th><label>Informacje</label></th>
                <!--<th><label>Newsletter</label></th>-->
                <th><label>Dane kontaktowe</label></th>
                <th><label>Godziny otwarcia punktu</label></th>
            </tr>
            </thead>
            <tbody style="z-index: -1; margin: 0 20px;">
            <form action="" method="post">
                <tr style="z-index: -1;">
                    <td><label><a href="index.php">Strona główna</a></label></td>
                    <td><label><a href="konto.php">Profil klienta</a></label></td>
                    <td><label><a href="regulamin.php">Regulamin</a></label></td>
                    <!--<td style="z-index: 0;"><label>Zapisz się do newslettera:</label></td>-->
                    <td style="z-index: 0;"><label>JOLA</label></td>
                    <td><label></label></td>
                </tr>
                <tr style="z-index: -1;">
                    <td><label><a href="produkty.php">Produkty</a></label></td>
                    <td><label><a href="lr.php">Logowanie</a></label></td>
                    <td><label><a href="odu.php">Odstąpienie od umowy</a></label></td>
                    <!--<td style="z-index: 0;"><input type="text" class="tb" style="width: 100%; margin: -4px;" name="email" placeholder="E-mail"></td>-->
                    <td style="z-index: 0;"><label>Puławska 11<br/> 02-515 Warszawa</label></td>
                    <td><label>Pon - Pt 11:00 - 19:00</label></td>
                </tr>
                <tr style="z-index: -1;">
                    <td><label><a href="promocje.php">Promocje</a></label></td>
                    <td><label></label></td>
                    <td><label><a href="pp.php">Polityka prywatności</a></label></td>
                    <!--<td style="z-index: 0;"><input type="submit" class="btn" style="margin: -2px 35%; height: 17px; padding: -2px;" name="ze" value="Zapisz"></td>-->
                    <td style="z-index: 0;"><a href="https://www.google.pl/maps/place/Pu%C5%82awska+11,+Warszawa/@52.2113718,21.0206028,19z/data=!3m1!4b1!4m5!3m4!1s0x471eccde67c5a7c5:0x429a96f791af49bd!8m2!3d52.211371!4d21.02115">Pokaż na mapie</a></td>
                    <td><label>Sob 11:00 - 15:00</label></td>
                </tr>
                <tr style="z-index: -1;">
                    <td><label><a href="kd.php">Koszty dostawy</a></label></td>
                    <td><label></label></td>
                    <td><label><a href="sp.php">Sposób płatności</a></label></td>
                    <!--<td style="z-index: 0;"><label class="control control--checkbox">Akceptuję politykę prywatności<input type="checkbox" name="anl"/><div class="control__indicator"></div></label></td>-->
                    <td style="z-index: 0;"><label>Telefon:  <a href="tel:+48000000000">+48 000 000 000</a></label></td>
                </tr>
                <tr style="z-index: -1;">
                    <td><label></label></td>
                    <td><label></label></td>
                    <td><label><a href="#"></a></label></td>
                    <!--<td style="z-index: 0;"><label><a href="#"></a></label></td>-->
                    <td style="z-index: 0;"><label>E-mail: <a href="mailto:kontakt@d.pl">kontakt@d.pl</a></label></td>
                </tr>
            </tbody>
            </form>
        </table>
    </div>
</body>
</html>';
                            mail($_POST['pemail'], "JOLA - Butik internetowy - Odzyskiwanie hasła dla konta " . $email, $ms, $headers);
                        } else {
                            echo '<h1>Brak konta z takim adresem e-mail.</h1>';
                            echo '<br/>&nbsp;<a href="ph.php">Spróbuj ponownie</a><br/>';
                        }
                    }
                    else
                    {
                        echo '<div class="error">Nie podano adresu e-mail.</div>';
                        echo '<br/>&nbsp;<a href="ph.php">Spróbuj ponownie</a><br/>';
                    }
                }
                else{
                    echo '<h1>Nie zaznaczono recaptchy.</h1><br/> &nbsp;<a href="ph.php">Spróbuj ponownie</a><br/>';
                }
            }
            ?>
            <br/>
            <h3>Jeśli nie aktywowałeś konta, a nie możesz się zalogować, to: </h3>
            <h4>- Musisz zarejestrować się jeszcze raz</h4>
            <h4>- Jesli nie możesz się zarejestrować to <a href="kontakt.php">zadzwoń do nas</a></h4>
            &nbsp; &nbsp;<a href="lr.php">Zarejestruj się teraz</a>
            <br/>
        </div>
    </div>
</div>
<?php include 'stopka.php'; ?>
</body>
</html>