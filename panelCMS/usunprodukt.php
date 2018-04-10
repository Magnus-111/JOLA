<?php
require '../polacz.php';
if (isset($_POST['t'])) {
    if (isset($_GET['idp'])) {
        mysqli_query($pol, 'DELETE FROM `produkty` WHERE `produkty`.`id` =' . $_GET['idp']);
        header("Location: admin.php?karta=1");
    }
}

if (isset($_POST['n'])) {
    header("Location: admin.php?karta=1");
}
?>
<label>Czy chcesz usunąć ten produkt?</label><br/><br/>
<?php
$p = mysqli_query($pol, "SELECT * FROM `produkty` WHERE id =" . $_GET['idp']);
while ($ep = mysqli_fetch_object($p)) {
    echo '<label>Nazwa: '.$ep->nazwa.'</label>';
}
    ?>
<br/><br/>
<form action="" method="POST">
    <input type="submit" class="btn" value="Tak" name="t">
    <input type="submit" class="btn" value="Nie" name="n">
</form>