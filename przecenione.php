<?php
/**
 * Created by PhpStorm.
 * User: Cezary
 * Date: 12.07.2017
 * Time: 16:46
 */
?>
<script type="text/javascript">
    var pobecneslidy = 3;

    function pplusSlides3(n) {
        pobecneslidy += n;
        ppokazSlajdow(pobecneslidy);
    }

    function ppokazSlajdow(n) {
        var pslajdy = document.getElementsByClassName("pSlides");
        if ( n >= pslajdy.length + 1 || n <= 0) { pobecneslidy = n = 3;}
        for (var m = 0; m < pslajdy.length; m++)
        {
            pslajdy[m].style.display = "none";
        }
        for (var k = n; k + 4 > n; k--)
        {
            pslajdy[k].style.display = "inline-block";
        }
    }

    function pkaruzela() {
        pplusSlides3(3);
    }

</script>
<div class="slideshow-container" style="display: flex; width: 100%;">
    <div class="slideShow">
        <?php
        $pp = mysqli_query($pol, 'SELECT * FROM `produkty` WHERE scena <> NULL AND dostep > 0');
        if (mysqli_num_rows($pp) < 1)
        {
            echo '<span style="position: relative; display: block; margin: 2px auto; left: 30%;">Brak przecenionych produktów.</span>';
        }
        //else if (mysqli_num_rows($pp) > 4)
        //{
        else {
            while ($op = mysqli_fetch_object($pp)) {
                echo '<div class="pSlides fade">';
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
                echo '<a class="gprev" onclick="pplusSlides3(-3);">&#10094;</a>
                  <a class="gnext" onclick="pplusSlides3(3);">&#10095;</a>';
            }
            echo '<script type="text/javascript">ppokazSlajdow(-1); setInterval(pkaruzela,7000);</script>';
        }
        /*else
        {
            while ($oppp = mysqli_fetch_object($pp)) {
                echo '<div class="pSlides fade">';
                echo '<div class="produktslide" title="Naciśnij, aby zobaczyć szczegóły produktu" onclick="location.href=\'produkt.php?idx=' . $oppp->id . '\';" >';
                echo '<div class="minimg" >';
                $rz = mysqli_query($pol, 'SELECT * FROM obr WHERE id_prod=' . $oppp->id . ' ORDER BY id ASC');
                if (mysqli_num_rows($rz) > 0) {
                    while ($zd = mysqli_fetch_object($rz)) {
                        echo '<img alt="z1" src="panelCMS/pimg/' . $zd->nazwa . '" />';
                        break;
                    }
                } else {
                    echo '<img alt="z2" src="ikon/prod.png" />';
                }
                echo '</div>';
                echo '<div class="etykp"><a href="produkt.php?idx=' . ($oppp->id) . '" >' . ($oppp->nazwa) . '</a><br />';
                //echo $produkt->opis . '<br>';
                echo 'Dostępność: ' . $oppp->dostep . ' szt<br />';
                if ($oppp->scena == NULL) {
                    echo '<span class="cena">' . number_format($oppp->cena, 2, ',', '.') . ' zł</span><br/>';
                } else {
                    echo '<span class="scena">' . number_format($oppp->scena, 2, ',', '.') . ' zł</span><br/>';
                    echo '<span class="acena">' . number_format($oppp->cena, 2, ',', '.') . ' zł</span><br/>';
                }

                echo '</div>';
                echo '<span><a class="dk" href="koszyk.php?id=' . ($oppp->id) . '">Do koszyka</a></span>';
                echo '</div>';
                echo '</div>';
            }
            echo '<script type="text/javascript">ppokazSlajdow(-1);</script>';
        }*/
        ?>

    </div>
</div>
<br>