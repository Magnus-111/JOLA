<?php
require 'polacz.php';
function post_captcha($user)
{
    $str = "";
    $pola = array('secret' => '6Lc8_xMUAAAAAGrK6I8jwwGfeK8lH7-tqmFSt2OS', 'reponse' => $user);
    foreach ($pola as $key=>$value)
    {
        $str .= $key .'='.$value.'&';
        $str = rtrim($str,'&');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST,count($pola));
    curl_setopt($ch, CURLOPT_POSTFIELDS,$str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $rez = curl_exec($ch);
    curl_close($ch);

    return json_decode($rez,true);
}


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        * {box-sizing:border-box}
        body {font-family: Verdana,sans-serif;margin:0}
        .mySlides {display:none}

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: #000000;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
        }

        .prev
        {
            left: 0;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor:pointer;
            height: 13px;
            width: 13px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active, .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
            position: relative;
            width: 33%;
        }

        @-webkit-keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
            .prev, .next,.text {font-size: 11px}
        }
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript">

    </script>
</head>
<body>
<div class="slideshow-container">
    <?php
    $np = mysqli_query($pol, 'SELECT * FROM `produkty` ORDER BY id DESC LIMIT 12');
    while ($op = mysqli_fetch_object($np)) {
        echo '<div class="mySlides fade">';
        echo '<div class="produkt" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx='.$op->id.'\';" >';
        echo '<div class="minimg" >';
        $rez = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $op->id.' ORDER BY id ASC');
        if (mysqli_num_rows($rez) > 0) {
            while ($z = mysqli_fetch_object($rez)) {
                echo '<img alt="z1" src="panelCMS/pimg/' . $z->nazwa . '" />';
                break;
            }
        } else {
            echo '<img alt="z2" src="ikon/prod.png" />';
        }
        echo '</div>';
        echo '<div class="etykp"><a href="produkt.php?idx=' . ($op->id) . '" >' . ($op->nazwa) . '</a><br />';
        //echo $produkt->opis . '<br>';
        echo 'Dostępność: ' . $op->dostep . ' szt<br />';
        if ($op->scena == NULL) {
            echo '<span class="cena">' . number_format($op->cena, 2, ',', '.') . ' zł</span><br/>';
        } else {
            echo '<span class="scena">' . number_format($op->scena, 2, ',', '.') . ' zł</span><br/>';
            echo '<span class="acena">' . number_format($op->cena, 2, ',', '.') . ' zł</span><br/>';
        }
        echo '<span><a class="dk" href="koszyk.php?id=' . ($op->id) . '">Do koszyka</a></span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <a class="prev" onclick="plusSlides(-2);">&#10094;</a>
    <a class="next" onclick="plusSlides(2);">&#10095;</a>

</div>
<br>

<!--<div style="text-align:center">
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
</div>-->

<form method="post" action="test.php">
    <div class="g-recaptcha" data-sitekey="6Lc8_xMUAAAAAFCKU-MF4TdX2EzqEqGmyAo51rH-"></div>
    <input type="submit" value="Wyślij" />
</form>

<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 10.07.2017
 * Time: 19:52
 */

function curl($url, $par)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($par));
    curl_setopt($ch, CURLOPT_POST, true);

    $header = array("Content-Length: ".strlen(http_build_query($par)),);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    return curl_exec($ch);
}

if (isset($_POST['g-recaptcha-response']))
{
    $resp_code = $_POST['g-recaptcha-response'];

    $tab = array(
        'secret' => '6Lc8_xMUAAAAAGrK6I8jwwGfeK8lH7-tqmFSt2OS',
        'response' => $resp_code,
    );
    
    $res = json_decode(curl("https://www.google.com/recaptcha/api/siteverify",$tab));

    if ($res->success != 1) {
        // What happens when the CAPTCHA wasn't checked
        echo '<p>Nie zaznaczono!!!</p><br>';
    } else {
        // If CAPTCHA is successfully completed...
        echo '<br><p>OK.</p><br>';
    }
}


?>

<script>
    var slideIndex = 3;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides3(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        //var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {slideIndex = 3;}

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "inline-block";
        slides[slideIndex-2].style.display = "inline-block";
        slides[slideIndex-3].style.display = "inline-block";
        //dots[slideIndex-3].className += " active";
    }

    function karuzela() {
        plusSlides(2);
    }
</script>
<script type="text/javascript">setInterval(karuzela, 4000);</script>
<script>

</script>
</body>
</html>