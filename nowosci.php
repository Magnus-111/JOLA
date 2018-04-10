<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 11.07.2017
 * Time: 21:17
 */
?>
<div class="slideshow-container" style="display: flex;">
    <div class="slideShow">
    <?php
    $np = mysqli_query($pol, 'SELECT * FROM `produkty` WHERE dostep > 0 ORDER BY id DESC LIMIT 8');
    while ($op = mysqli_fetch_object($np)) {
        echo '<div class="nSlides fade">';
        echo '<div class="produktslide" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx='.$op->id.'\';" >';
        echo '<div class="minimg" >';
        $rez = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $op->id . ' ORDER BY id ASC LIMIT 1');
        if (mysqli_num_rows($rez) > 0) {
            while ($z = mysqli_fetch_object($rez)) {
                echo '<img alt="z1" src="panelCMS/pimg/' . $z->nazwa . ' " />';
            }
        } else {
            echo '<img alt="z1" src="ikon/prod.png" />';
        }
        echo '</div>';
        echo '<div class="etykp"><a style="font-size: 120%;" href="produkt.php?idx=' . ($op->id) . '" >' . ($op->nazwa) . '</a><br/><br/>';
        if ($op->dostep < 1)
        {
            echo '<span style="font-style: italic; color: #ff0000; font-weight: 600;">Dostępność: ' . $op->dostep . ' szt</span><br />';
        }
        elseif ($op->dostep < 4)
        {
            echo '<span style="font-style: italic; color: #ff8900; font-weight: 600;">Dostępność: ' . $op->dostep . ' szt</span><br />';
        }
        else
        {
            echo '<span style="font-style: italic; font-weight: 600;">Dostępność: ' . $op->dostep . ' szt</span><br />';
        }
        if ($op->scena == NULL) {
            echo '<span class="cena">' . number_format($op->cena, 2, ',', '.') . ' zł</span><br/>';
        } else {
            echo '<span class="scena">' . number_format($op->scena, 2, ',', '.') . ' zł</span><br/>';
            echo '<span class="acena">' . number_format($op->cena, 2, ',', '.') . ' zł</span><br/>';
        }

        echo '</div>';
        echo '<span><a class="dk" title="Naciśnij, aby dodać do koszyka" href="koszyk.php?id=' . ($op->id) . '">Do koszyka</a></span>';
        echo '</div></div>';
    }
    ?>
    <a class="gprev" onclick="show(-4);">&#10094;</a>
    <a class="gnext" onclick="show(4);">&#10095;</a>
    </div>
</div>
<br>
<script type="text/javascript">

    // do poprawki
    var obecnenowe = 0;

    function hide() {
        var nowe = document.getElementsByClassName("nSlides");
        for(var i = 0; i < nowe.length; i++)
        {
            nowe[i].style.display = "none";
        }
    }

    function show(io) {
        hide();
        var nowe = document.getElementsByClassName("nSlides");
        if (io < 0) {obecnenowe += io;}
        if (obecnenowe < 0) {obecnenowe = (nowe.length - 4);}
        for (var i = obecnenowe; i < obecnenowe + Math.abs(io); i++)
        {
            if (i < nowe.length)
            {
                nowe[i].style.display = "inline-block";
            }
        }
        if (io > 0) {obecnenowe += io;}
        if (obecnenowe >= nowe.length - 1) {obecnenowe = 0;}
    }

    show(4);

    function nkaruzela() {
        show(4);
    }
</script>
<script type="text/javascript">setInterval(nkaruzela,7000);</script>