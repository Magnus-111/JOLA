<script src='https://www.google.com/recaptcha/api.js'></script>
<div id="pasek1">
    <!--<a href="">PL</a><a href="">PLN</a>-->
    <div id="mpp" onclick="toggleMenu();">
        <img id="ikotog" src="ikon/kp.png" height="256" />
    </div>
    <div id="paseklr">
        <span><a class="kbtn" href="javascript:void(0);" onclick=" pum('#paseklr'); pum('#paseklogowania');">Logowanie</a></span>
        <span><a class="kbtn" href="lr.php">Rejestracja</a></span>
    </div>
    <div id="pasekpomocy">
        <a class="kbtn" href="kontakt.php">Kontakt</a>
        <a class="kbtn" href="pomoc.php">Pomoc</a>
    </div>
    <div id="paseklogowania">
        <?php
        require 'loginf.php';
        $emailM = null;
        if (isset($_SESSION['e_logowania'])) {
            echo '<div class="error"> '.$_SESSION['e_logowania'].' <span class="zamknijbtn" onclick="this.parentElement.style.display=\'none\';">&times;</span></div>';
            unset($_SESSION['e_logowania']);
        }
        if (isset($_SESSION['email'])) {
            $emailM = $_SESSION['email'];
            $_SESSION['lbl'] = 0;
        }
        $formli = '<form name="loginbar" action="" method="post" style="margin: 5px 8px;" enctype="multipart/form-data" accept-charset="UTF-8">
                <span style="height: 100%; font-weight: bold;" >Zalogowany(a) jest:&nbsp;&nbsp;</span><a href="konto.php">' . $emailM . '</a>
                <input type="submit" class="btn" style="margin: -3px 4px;" value="Wyloguj" name="btn_logout" /></form>
                <script type="text/javascript"> pum(\'#paseklogowania\'); </script>';
        $formlo = '<form name="loginbar" action="" method="post" style="white-space: normal;" enctype="multipart/form-data" accept-charset="UTF-8">
                   <input class="btn" style="margin: 0px 4px; padding: -1px -2px;" type="button" value="❮" name="schowaj" onclick=" pum(\'#paseklogowania\'); pum(\'#paseklr\');" />
                   <input class="tb" placeholder="E-mail" type="text" name="email"/>
                   <input class="tb" placeholder="Hasło" type="password" name="haslo"/>
                   <input type="submit" class="btn" style="margin: 0px 4px;"  value="Zaloguj" name="btn_login" /></form>
                   ';
        if (!empty($_SESSION['email'])) {
            echo $formli;
        } else {
            echo $formlo;
        }

        if (isset($_POST['schowaj']))
        {
            $_SESSION['lbl'] = 0;
        }

        if (isset($_SESSION['lbl'])) {
            if ($_SESSION['lbl'] > 3) {
                echo '<br/><div><div class="g-recaptcha" data-sitekey="6Lc8aEAUAAAAAKqz3tecyqzHGvCPQJ-BP2Toa6gy"></div></div>';
            }
        }
        ?>
    </div>
</div>
<?php
if (empty($_SESSION['email']) || isset($_SESSION['e_email']) || isset($_SESSION['e_haslo'])) {
    echo '<script type="text/javascript" > pum(\'paseklogowania\'); pum(\'paseklr\');</script>';
}
if (!empty($_SESSION['email']) || !isset($_SESSION['e_email']) || !isset($_SESSION['e_haslo'])) {
    echo '<script type="text/javascript" >pum(\'paseklr\'); pum(\'paseklogowania\');</script>';
}

?>