<?php
require 'koszykf.php';
if (isset($_POST['szp'])) {
header("Location: szukaj.php?sz=".$_POST['szp']);
}

?>
<div id="belka">
    <span style="margin: -2px -8px 0 3px; padding: 3px; position: relative; float: left; font-style: italic; font-weight: bold; font-size: xx-large; text-shadow: 3px 3px #c9c9c9; text-decoration: none;">
        <a style="color: #ffffff; text-decoration: none; letter-spacing: 2px;" href="index.php">JOLA</a>
    </span>
    <div id ="pasekkoszyk" onclick="location.href = 'koszyk.php';">
        <span  style="display: inline-block; background: url('ikon/koszyk.png'); background-repeat: no-repeat; background-size: 96% 102%; background-position-x: 2px; background-position-y: -1px; padding: 13px; font-weight: bold; margin: 0px;">
            <?php
            if (isset($_SESSION['koszyk'])) {
                echo '&nbsp;' . count($_SESSION['koszyk']) . '&nbsp;';
            } else {
                echo '&nbsp;0&nbsp;';
            }
            ?>
        </span>
        <?php
        if ($_SESSION['astr'] != 'zamow') {
            if (wartosckoszyka() <= 0) {
                echo '<span style="margin: 6px 0 9px 0; letter-spacing: 1px;">Koszyk jest pusty</span>';
            } else {

                echo '<span style="margin-left: 5px; margin-right: 10px; padding-top: 7px; letter-spacing: 1px;">' . number_format(wartosckoszyka(), 2, ',', ' ') . ' zł</span>';
            }
        }
        ?>
        <a href="koszyk.php" style="position: relative; float: right; margin: -8px -9px -11px 5px; background-color: #8c8c8c; padding: 21px 14px; color: #fff; text-decoration: none; font-size: xx-large;" >❯</a>
    </div>
    <div id="wyszukiwarka">
        <form action="" method="post">
            <div class="search-wrapper" title="Wyszykaj produkt">
                <div class="input-holder">
                    <input type="text" class="search-input" name="szp" placeholder="Znajdź produkt po id, nazwie, typie" />
                    <button class="search-icon" onclick="searchToggle(this, event);"><span><input type="submit" style="display: none;" value=""/></span></button>
                </div>
                <span class="close" onclick="searchToggle(this, event);"></span>
            </div>
        </form>
    </div>

</div>